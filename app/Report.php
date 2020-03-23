<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    protected $fillable = ['date', 'country_id', 'province_id', 'deaths', 'confirmed', 'recovered'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Report::class);
    }
}
