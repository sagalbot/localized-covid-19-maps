<?php

namespace App\Console\Commands;

use App\JohnsHopkinsRepository;
use App\LastUpdate;
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
    protected $description = 'Scrape and seed the database.';

    /**
     * Local storage/app/{DIRECTORY} path
     */
    const DIRECTORY = 'covid-data';

    /**
     * The path to daily reports within the repository.
     */
    const TIME_SERIES = 'csse_covid_19_data/csse_covid_19_time_series';

    const REPORTS_PATH = 'csse_covid_19_data/csse_covid_19_daily_reports';

    const CONFIRMED = 'time_series_covid19_confirmed_global.csv';
    const DEATHS = 'time_series_covid19_deaths_global.csv';
    const RECOVERED = 'time_series_covid19_recovered_global.csv';

    const US_CONFIRMED = 'time_series_covid19_confirmed_US.csv';
    const US_DEATHS = 'time_series_covid19_deaths_US.csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh') || !Storage::exists(self::DIRECTORY)) {
            $this->info('Cloning fresh repository...');
            Storage::deleteDirectory(self::DIRECTORY);
            shell_exec("git clone https://github.com/CSSEGISandData/COVID-19.git {$this->repositoryPath()}");
        } else {
            $this->info('Pulling repository...');
            $this->line(shell_exec("cd {$this->repositoryPath()} && git pull"));
        }

        try {
            DB::beginTransaction();

            $this->resetDatabase();

            Cache::flush();

            collect([self::CONFIRMED, self::DEATHS, self::RECOVERED])->each(function ($file) {
                $this->line("Importing {$file} \n");
                $records = collect($this->getSeries($file)->getRecords());

                $bar = $this->output->createProgressBar($records->count());
                $bar->start();

                $records->each(function ($row) use ($bar, $file) {
                    $this->findOrCreateModels($row, $file);
                    $bar->advance();
                });

                $bar->finish();
                $this->line("\n");
            });

            Cache::flush();

            DB::commit();

            LastUpdate::create([
                'status' => 'ok',
            ]);
        } catch (\Exception $e) {
            dump($e->getMessage());
            DB::rollBack();

            LastUpdate::create([
                'status' => 'fail',
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param array $row
     * @param string $file
     */
    public function findOrCreateModels(array $row, string $file): void
    {
        $metric = collect(['deaths', 'confirmed', 'recovered'])->first(function ($type) use ($file) {
            return Str::contains($file, $type);
        });

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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function dailyReports()
    {
        return collect(Storage::files($this->dailyReportsPath()))->filter(function ($path) {
            return Str::contains($path, '.csv');
        });
    }

    /**
     * Path to the locally cloned repository.
     *
     * @return string
     */
    public function repositoryPath()
    {
        return storage_path('app/' . self::DIRECTORY);
    }

    public function dailyReportsPath()
    {
        return self::DIRECTORY . '/' . self::REPORTS_PATH;
    }

    /**
     * @param $name
     * @return \League\Csv\AbstractCsv
     * @throws \League\Csv\Exception
     */
    public function getSeries($name): AbstractCsv
    {
        $file = Storage::get(self::DIRECTORY . '/' . self::TIME_SERIES . '/' . $name);

        $csv = Reader::createFromString($file);
        $csv->setHeaderOffset(0);

        return $csv;
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
