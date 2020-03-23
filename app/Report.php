<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Report::class);
    }
}
