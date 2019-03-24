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
            
            [ 'code' => 'account'      , 'title' => 'My account'   , 'url' => '/account'       , 'icon' => 'fas fa-user-circle'             , 'parent_name' => null       , 'order' => 0 , 'menu' => true],
            [ 'code' => 'user.porfile'      , 'title' => 'Profile'      , 'url' => '/user/porfile'       , 'icon' => 'fas fa-user text-teal'          , 'parent_name' => 'account'  , 'order' => 0 , 'menu' => true],
            [ 'code' => 'user.setting'      , 'title' => 'Setting'      , 'url' => '/user/setting'       , 'icon' => 'fas fa-cog text-blue'          , 'parent_name' => 'account'  , 'order' => 1 , 'menu' => true],
           

            [ 'code' => 'teachers'     , 'title' => 'Teachers'     , 'url' => '/teachers'      , 'icon' => 'fas fa-graduation-cap'          , 'parent_name' => null       , 'order' => 1 , 'menu' => true],
            [ 'code' => 'teachers.all' , 'title' => 'All Teachers' , 'url' => '/teachers/all'  , 'icon' => 'fas fa-user-graduate text-teal' , 'parent_name' => 'teachers' , 'order' => 0 , 'menu' => true],
            
            [ 'code' => 'lessons'      , 'title' => 'Lessons'      , 'url' => '/lessons'       , 'icon' => 'fas fa-pencil-ruler'            , 'parent_name' => null       , 'order' => 2 , 'menu' => true],
            [ 'code' => 'basic'        , 'title' => 'Basic'        , 'url' => '/lessons/basic' , 'icon' => 'fas fa-pencil-alt text-teal'    , 'parent_name' => 'lessons'  , 'order' => 0 , 'menu' => true],
            
            [ 'code' => 'user'         , 'title' => 'Users'        , 'url' => '/user'          , 'icon' => 'fas fa-user-friends'            , 'parent_name' => null       , 'order' => 3 , 'menu' => true],
            
        ]);
    }
}
