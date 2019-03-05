<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TimeZone extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'time_zones';  
    
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_code');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'country_code', 'code');
    }
}
