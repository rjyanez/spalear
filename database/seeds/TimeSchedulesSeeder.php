<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER SEQUENCE time_schedules_id_seq RESTART WITH 1'); 
        
        $teachers = DB::table('users')->select('id')->where('rol_code', 'TE')->get();
        $data = [];
        $min = [0,30];

        foreach ($teachers as $key => $value) {
            
            for($i=0; $i < rand (1,3); $i++){
                array_push($data,[
                    'user_id' => $value->id,
                    'week'     => rand (0   ,6),
                    'hour'    => rand (0   ,23).':'.$min[rand(1,1)],
                ]);
            }
        }

        DB::table('time_schedules')->insert($data);
    
    }
}
