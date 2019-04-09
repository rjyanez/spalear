<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StudensTeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::statement('ALTER SEQUENCE students_teachers_id_seq RESTART WITH 1'); 

        $data = [];
        
        $teachers = App\User::select('id')
                            ->whereHas('roles',function ($q){
                                $q->where('key', 'TE');
                            })
                            ->get();
        $students = App\User::select('id')
                                ->where(function ($query) {
                                    $query->whereHas('roles',function ($q){
                                        $q->where('key', 'ST');
                                    });
                                })->get();


        foreach ($teachers as $key => $value) {
            $studentTake = $students->random(rand (3,5));
            foreach ($studentTake as $key => $student) {
	    		array_push($data,[
	                'teacher_id' => $value->id,
	                'student_id' => $student->id,
	                'ranking'    => rand (0,5),
	                'favorite'   => rand (0,1),
	            ]);
	        }
        }

        DB::table('students_teachers')->insert($data);
    }
}
