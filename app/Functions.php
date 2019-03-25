<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Functions extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'functions';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public function roles()
    {
        return $this->belongsToMany('App\CodeMeta','functions_roles','function_code','rol_code');

    }
}
