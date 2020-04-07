<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\AbstractCsv;
use League\Csv\Reader;

class JohnsHopkinsRepository
{
    /**
     * Local storage/app/{DIRECTORY} path
     */
    const DIRECTORY = 'covid-data';

    /**
     * The path to daily reports within the repository.
     */
    const TIME_SERIES = 'csse_covid_19_data/csse_covid_19_time_series';

    const CONFIRMED = 'time_series_covid19_confirmed_global.csv';

    const DEATHS = 'time_series_covid19_deaths_global.csv';

    const RECOVERED = 'time_series_covid19_recovered_global.csv';

    const US_CONFIRMED = 'time_series_covid19_confirmed_US.csv';

    const US_DEATHS = 'time_series_covid19_deaths_US.csv';

    const DAILY_REPORTS = 'csse_covid_19_data/csse_covid_19_daily_reports';

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function dailyReports(): Collection
    {
        return collect(Storage::files(self::dailyReportsPath()))->filter(function ($path) {
            return Str::contains($path, '.csv');
        });
    }

    public static function exists(): bool
    {
        return Storage::exists(self::DIRECTORY);
    }

    public static function doesNotExist(): bool
    {
        return !self::exists();
    }

    public static function delete(): void
    {
        Storage::deleteDirectory(self::DIRECTORY);
    }

    /**
     * Path to the locally cloned repository.
     *
     * @return string
     */
    public static function path(): string
    {
        return storage_path('app/' . self::DIRECTORY);
    }

    public static function dailyReportsPath(): string
    {
        return self::DIRECTORY . '/' . self::DAILY_REPORTS;
    }

    public static function series(): Collection
    {
        return collect([
            'confirmed' => self::CONFIRMED,
            'recovered' => self::RECOVERED,
            'deaths' => self::DEATHS,
        ]);
    }

    public static function getRecords($path): Collection
    {
        return collect(self::getSeries($path)->getRecords());
    }

    public static function getSeries($path): AbstractCsv
    {
        $file = Storage::get(self::DIRECTORY . '/' . self::TIME_SERIES . '/' . $path);

        $csv = Reader::createFromString($file);
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
