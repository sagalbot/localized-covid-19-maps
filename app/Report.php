<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    public $dates = ['date'];

    protected $fillable = ['date', 'country_id', 'province_id', 'deaths', 'confirmed', 'recovered'];

    public function getCreatedAtColumn()
    {
        return 'date';
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Report::class);
    }
}
