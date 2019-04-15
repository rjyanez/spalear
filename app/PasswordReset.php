<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{

    protected $primaryKey = 'email';
    protected $keyType = 'string';

    protected $fillable = [
        'email', 'token'
    ];
}
