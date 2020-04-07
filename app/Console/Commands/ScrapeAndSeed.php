<?php

namespace App\Console\Commands;

use App\Jobs\ImportCovidData;
use App\JohnsHopkinsRepository;
use App\Report;
use App\Country;
use App\Province;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use League\Csv\AbstractCsv;
use League\Csv\Reader;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class ScrapeAndSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull the latest data and dispatch an import job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->cloneOrPullRepository();

        try {
            DB::beginTransaction();

            $this->resetDatabase();

            Cache::flush();

            JohnsHopkinsRepository::series()->each(function ($file, $metric) {
                $records = JohnsHopkinsRepository::getRecords($file);

                $bar = $this->createAndStartProgress($records->count(), $metric);

                $records->each(function ($row) use ($bar, $metric) {
                    $this->findOrCreateModels($row, $metric);
                    $bar->advance();
                });

                $bar->finish();
                $this->line("\n");
            });

            Cache::flush();

            DB::commit();
        } catch (\Exception $e) {
            dump($e->getMessage());
            DB::rollBack();
        }
    }

    protected function createAndStartProgress(int $count, string $metric): ProgressBar
    {
        $this->line("Importing {$metric}...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        return $bar;
    }

    protected function cloneOrPullRepository(): void
    {
        $path = JohnsHopkinsRepository::path();

        if ($this->option('fresh') || JohnsHopkinsRepository::doesNotExist()) {
            $this->info('Cloning fresh repository...');
            JohnsHopkinsRepository::delete();
            shell_exec("git clone https://github.com/CSSEGISandData/COVID-19.git {$path}");
        } else {
            $this->info('Pulling repository...');
            $this->line(shell_exec("cd {$path} && git pull"));
        }
    }

    /**
     * @param array $row
     * @param string $metric
     */
    public function findOrCreateModels(array $row, string $metric): void
    {
        $country = $this->determineCountryId($row);
        $province = $this->determineProvinceId($row, $country);

        collect($row)->each(function ($value, $date) use ($metric, $province, $country) {
            if ($this->isDateColumn($date)) {
                $reportCacheKey = "{$date}-{$country}-{$province}";

                if (Cache::get($reportCacheKey)) {
                    $report = Cache::get($reportCacheKey);
                } else {
                    $report = Report::firstOrCreate([
                        'date' => Carbon::createFromFormat('n/j/y', $date),
                        'country_id' => $country,
                        'province_id' => $province,
                    ]);
                    Cache::put($reportCacheKey, $report);
                }

                $report->update([
                    $metric => $value,
                ]);
            }
        });
    }

    public function isDateColumn(string $date): bool
    {
        return sizeof(explode('/', $date)) === 3 && checkdate(...explode('/', $date));
    }

    public function getProvinceName(array $report)
    {
        $keys = ['Province/State', 'Province_State'];

        return $report[$this->determineNamingConvention($keys, $report)] ?? '';
    }

    public function getCountryName(array $report)
    {
        $keys = ['Country/Region', 'Country_Region'];

        return $report[$this->determineNamingConvention($keys, $report)] ?? '';
    }

    /**
     * @param array $possibilities
     * @param array $report
     * @return mixed
     */
    public function determineNamingConvention(array $possibilities, array $report)
    {
        return collect($possibilities)
            ->filter(function ($convention) use ($report) {
                return array_key_exists($convention, $report);
            })
            ->first();
    }

    protected function resetDatabase(): void
    {
        DB::table('reports')->delete();
    }

    /**
     * @param array $row
     * @return int
     */
    protected function determineCountryId(array $row): int
    {
        $name = $this->getCountryName($row);
        $key = "Country-{$name}";

        if (Cache::get($key)) {
            return Cache::get($key);
        }

        $country = Country::firstOrCreate([
            'name' => $name,
        ])->id;

        Cache::put($key, $country);

        return $country;
    }

    /**
     * @param array $row
     * @param int $country
     * @return int|null
     */
    protected function determineProvinceId(array $row, int $country)
    {
        $name = $this->getProvinceName($row);

        if (!$name) {
            return null;
        }

        $key = "Province-{$name}";

        if (Cache::get($key)) {
            return Cache::get($key);
        }

        $province = Province::firstOrCreate([
            'name' => $name,
            'country_id' => $country,
        ])->id;

        Cache::put($key, $province);

        return $province;
    }
}
