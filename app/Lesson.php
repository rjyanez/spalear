<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Lesson extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'lessons';

    public function level()
    {
        return $this->belongsTo(CodeMeta::class, 'level_code');
    }

}
