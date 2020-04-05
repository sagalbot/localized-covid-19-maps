<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

trait HasReports
{
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function latestReport(): HasOne
    {
        return $this->hasOne(Report::class)->take(1);
    }

    public function timeSeries(): Collection
    {
        return $this->reports
            ->groupBy(function (Report $report) {
                return $report->date->serialize();
            })
            ->map(function (Collection $reports, $date) {
                $report = new Report(['date' => Carbon::fromSerialized($date)]);

                $reports->each(function ($date) use ($report) {
                    $report->country_id = $date->country_id;
                    $report->province_id = $date->province_id ?: null;
                    $report->confirmed += $date->confirmed;
                    $report->recovered += $date->recovered;
                    $report->deaths += $date->deaths;
                });

                return $report;
            })
            ->values();
    }
}
