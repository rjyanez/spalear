<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER SEQUENCE lessons_id_seq RESTART WITH 1'); 

        $data =[];

        $levels = ['BAS','MED','ADV'];
        foreach ($levels as $level) {
            for($i=0; $i < rand (5,10); $i++){

                array_push($data,[
                    'level_code' => $level,
                    'name'       => ($i+1),
                    'description'=> "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel sem pretium, volutpat mauris sed, maximus est. Sed sollicitudin rutrum odio, a efficitur urna tincidunt ut.",
                ]);
            }
        }

        DB::table('lessons')->insert($data);
    }
}
