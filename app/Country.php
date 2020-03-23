<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasReports;

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
