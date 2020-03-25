<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasReports;

    protected $fillable = ['name'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    //public function reports()
    //{
    //    return $this->belongsToMany(Report::class);
    //}
}
