<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasReports;

    protected $fillable = ['name', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
