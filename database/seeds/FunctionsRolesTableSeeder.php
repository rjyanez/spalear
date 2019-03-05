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
        DB::table('functions_roles')->truncate();

        DB::table('functions_roles')->insert([
            [ 'function_code' => 'account' , 'rol_code' => 'AD' ],
            [ 'function_code' => 'account' , 'rol_code' => 'SC' ],
            [ 'function_code' => 'account' , 'rol_code' => 'TE' ],
            [ 'function_code' => 'account' , 'rol_code' => 'ST' ],

            [ 'function_code' => 'porfile' , 'rol_code' => 'AD' ],
            [ 'function_code' => 'porfile' , 'rol_code' => 'SC' ],
            [ 'function_code' => 'porfile' , 'rol_code' => 'TE' ],
            [ 'function_code' => 'porfile' , 'rol_code' => 'ST' ],

            [ 'function_code' => 'user'    , 'rol_code' => 'AD' ],
            [ 'function_code' => 'user'    , 'rol_code' => 'SC' ]

            // [ 'function_code' => 'teachers', 'rol_code' => 'AD' ],
            // [ 'function_code' => 'lessons' , 'rol_code' => 'AD' ],
            // [ 'function_code' => 'basic'   , 'rol_code' => 'AD' ],
        ]);
    }
}
