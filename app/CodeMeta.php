<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodeMeta extends Model
{
    protected $table = 'codes_meta';
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['type','key','value'];

	public function functions()
    {
        return $this->belongsToMany('App\Functions','functions_roles','rol_code','function_code');

    }

    public function classesStatus()
    {
        return $this->hasMany(Classes::class, 'key', 'status_code');
    }

    public function classesTypes()
    {
        return $this->hasMany(Classes::class, 'key', 'type_code');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'key', 'rol_code');
    }

    public function lessons()
    {
        return $this->hasMany(Classes::class, 'key', 'level_code');
    }
}
