<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'roles';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $fillable = ['code','name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function functions()
    {
        return $this->belongsToMany('App\Functions','functions_roles','rol_code','function_code');

    }
}
