<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        
        DB::table('roles')->insert([
            [ 'code' => 'AD', 'name' => 'Admin'],
            [ 'code' => 'SC', 'name' => 'Scheduler'],
            [ 'code' => 'TE', 'name' => 'Teacher'],
            [ 'code' => 'ST', 'name' => 'Student'],
        ]);
    }
}
