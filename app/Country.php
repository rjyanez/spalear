<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Country extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'countries';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public function timeZones()
    {
        return $this->hasMany('App\TimeZone', 'country_code', 'code');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'country_code', 'code');
    }
}
