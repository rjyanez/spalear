<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'meetings';

    protected $fillable =   [
        'id',
        'status_code', 
        'type_code', 
        'url',
        'student_id', 
        'teacher_id',
        'lesson_id',
        'date'
    ];

    public function status()
    {
        return $this->belongsTo(CodeMeta::class, 'status_code');
    }

    public function type()
    {
        return $this->belongsTo(CodeMeta::class, 'type_code');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

}
