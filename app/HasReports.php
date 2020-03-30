<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

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
}
