<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FunctionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('functions')->insert([
            
            [ 'code' => 'account'      , 'title' => 'My account'   , 'url' => '/account'       , 'icon' => 'ni-single-02'                  , 'parent_name' => null       , 'order' => 0 , 'menu' => true],
            [ 'code' => 'porfile'      , 'title' => 'Profile'      , 'url' => '/porfile'       , 'icon' => 'ni-single-02 text-yellow'      , 'parent_name' => 'account'  , 'order' => 0 , 'menu' => true],
            [ 'code' => 'teachers'     , 'title' => 'Teachers'     , 'url' => '/teachers'      , 'icon' => 'ni-hat-3'                      , 'parent_name' => null       , 'order' => 1 , 'menu' => true],
            [ 'code' => 'teachers.all' , 'title' => 'All Teachers' , 'url' => '/teachers/all'  , 'icon' => 'ni-bullet-list-67 text-yellow' , 'parent_name' => 'teachers' , 'order' => 0 , 'menu' => true],
            [ 'code' => 'lessons'      , 'title' => 'Lessons'      , 'url' => '/lessons'       , 'icon' => 'ni-ruler-pencil'               , 'parent_name' => null       , 'order' => 2 , 'menu' => true],
            [ 'code' => 'basic'        , 'title' => 'Basic'        , 'url' => '/lessons/basic' , 'icon' => 'ni-pin-3 text-yellow'          , 'parent_name' => 'lessons'  , 'order' => 0 , 'menu' => true],
            [ 'code' => 'user'         , 'title' => 'Users'        , 'url' => '/user'          , 'icon' => 'ni-circle-08'                  , 'parent_name' => null       , 'order' => 3 , 'menu' => true],
            
        ]);
    }
}
