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
            [ 'function_code' => 'user', 'rol_code' => 'AD' ],
            [ 'function_code' => 'user', 'rol_code' => 'SC' ],
            [ 'function_code' => 'teacher', 'rol_code' => 'ST' ],
            [ 'function_code' => 'teacher.favorite', 'rol_code' => 'ST' ],
            [ 'function_code' => 'lessons' , 'rol_code' => 'ST' ]
        ]);
    }
}
