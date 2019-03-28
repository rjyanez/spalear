<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FunctionsRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER SEQUENCE functions_roles_id_seq RESTART WITH 1'); 
        // DB::table('functions_roles')->truncate();

        DB::table('functions_roles')->insert([
            [ 'function_code' => 'account' , 'rol_code' => 'AD' ],
            [ 'function_code' => 'account' , 'rol_code' => 'SC' ],
            [ 'function_code' => 'account' , 'rol_code' => 'TE' ],
            [ 'function_code' => 'account' , 'rol_code' => 'ST' ],

            [ 'function_code' => 'user.profile' , 'rol_code' => 'AD' ],
            [ 'function_code' => 'user.profile' , 'rol_code' => 'SC' ],
            [ 'function_code' => 'user.profile' , 'rol_code' => 'TE' ],
            [ 'function_code' => 'user.profile' , 'rol_code' => 'ST' ],

            [ 'function_code' => 'user.setting' , 'rol_code' => 'AD' ],
            [ 'function_code' => 'user.setting' , 'rol_code' => 'SC' ],
            [ 'function_code' => 'user.setting' , 'rol_code' => 'TE' ],
            [ 'function_code' => 'user.setting' , 'rol_code' => 'ST' ],            

            [ 'function_code' => 'user', 'rol_code' => 'AD' ],
            [ 'function_code' => 'user', 'rol_code' => 'SC' ],

            [ 'function_code' => 'teachers', 'rol_code' => 'AD' ],
            [ 'function_code' => 'teachers', 'rol_code' => 'SC' ],
            [ 'function_code' => 'teachers', 'rol_code' => 'TE' ],
            [ 'function_code' => 'teachers', 'rol_code' => 'ST' ],

            [ 'function_code' => 'teachers.all', 'rol_code' => 'AD' ],
            [ 'function_code' => 'teachers.all', 'rol_code' => 'SC' ],
            [ 'function_code' => 'teachers.all', 'rol_code' => 'TE' ],
            [ 'function_code' => 'teachers.all', 'rol_code' => 'ST' ],


            // [ 'function_code' => 'lessons' , 'rol_code' => 'AD' ],
            // [ 'function_code' => 'basic'   , 'rol_code' => 'AD' ],
        ]);
    }
}
