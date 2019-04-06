<?php

namespace App;

use App\User;


class Teacher extends User
{

    public function students()
    {
        return $this->belongsToMany(User::class,'students_teachers','teacher_id','student_id')
                                    ->withPivot('ranking', 'favorite');
    }

    public function lessons()
    {
        return $this->hasMany(Classes::class,'teacher_id','id');
    }

}
