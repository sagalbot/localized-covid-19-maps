<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasReports;

    protected $fillable = ['name'];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function provincesWithLatestReport(): HasMany
    {
        return $this->provinces()->with('latestReport');
    }
}
