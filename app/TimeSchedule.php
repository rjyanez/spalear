<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSchedule extends Model
{
    protected $table = 'time_schedules';  
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
