<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kodeine\Metable\Metable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes, Metable;

    protected $dates =      [
        'deleted_at'
    ];
    protected $fillable =   [
        'id',
        'name',
        'email',
        'password',
        'country_code',
        'time_zone_id',
        'avatar',
        'description',
        'rol_code',
        'online',
    ];
    protected $hidden =     [
        'password',
        'remember_token'
    ];

    protected $metaTable =  'users_meta';

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function rol()
    {
        return $this->belongsTo(CodeMeta::class, 'rol_code');
    }

    public function roles()
    {
        return $this->belongsToMany(CodeMeta::class, 'users_roles', 'user_id', 'rol_code');
    }

    public function timeZone()
    {
        return $this->belongsTo(TimeZone::class, 'time_zone_id');
    }

    public function timeSchedule()
    {
        return $this->hasMany(TimeSchedule::class, 'user_id', 'id');
    }

    public function receivedMessages()
    {
        return $this->belongsToMany(User::class, 'messages', 'to_id', 'from_id')
            ->withPivot('id', 'message', 'created_at');
    }

    public function sentMessages()
    {
        return $this->belongsToMany(User::class, 'messages', 'from_id', 'to_id')
            ->withPivot('id', 'message', 'created_at');
    }

    public function studentTeachers()
    {
        return $this->belongsToMany(User::class, 'students_teachers', 'student_id', 'teacher_id')
            ->withPivot('ranking', 'favorite');
    }

    public function studentMeetings()
    {
        return $this->hasMany(Meeting::class, 'student_id', 'id');
    }

    public function teacherStudents()
    {
        return $this->belongsToMany(User::class, 'students_teachers', 'teacher_id', 'student_id')
            ->withPivot('ranking', 'favorite');
    }

    public function teacherMeetings()
    {
        return $this->hasMany(Meeting::class, 'teacher_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
