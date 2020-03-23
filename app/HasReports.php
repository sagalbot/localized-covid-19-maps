<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasReports
{
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
