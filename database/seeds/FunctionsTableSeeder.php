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
            
            [ 'code' => 'account'       , 'title' => 'My account' , 'url' => '/account'       , 'icon' => 'fas fa-user-circle fa-lg'   , 'parent_name' => null      , 'order' => 0 , 'menu' => true],
            [ 'code' => 'user.setting'  , 'title' => 'Setting'    , 'url' => '/user/setting'  , 'icon' => 'fas fa-cog text-teal' , 'parent_name' => 'account' , 'order' => 0 , 'menu' => true],
            [ 'code' => 'user.password' , 'title' => 'Password'   , 'url' => '/user/password' , 'icon' => 'fas fa-key text-blue' , 'parent_name' => 'account' , 'order' => 1 , 'menu' => true],
            

            [ 'code' => 'teacher'          , 'title' => 'Teachers'          , 'url' => '/teacher'          , 'icon' => 'fas fa-graduation-cap'          , 'parent_name' => null       , 'order' => 1 , 'menu' => true],
            [ 'code' => 'teacher.all'      , 'title' => 'All Teachers'      , 'url' => '/teacher/all'      , 'icon' => 'fas fa-user-graduate text-teal' , 'parent_name' => 'teacher' , 'order' => 0 , 'menu' => true],
            [ 'code' => 'teacher.favorite' , 'title' => 'Favorite Teacher' , 'url' => '/teacher/favorite' , 'icon' => 'fas fa-heart text-blue' , 'parent_name' => 'teacher' , 'order' => 1 , 'menu' => true],
            
            [ 'code' => 'student.favorite', 'title' => 'My Students', 'url' => '/student/favorite', 'icon' => 'fas fa-book-reader', 'parent_name' => null, 'order' => 0, 'menu' => true],

            [ 'code' => 'lessons'          , 'title' => 'Lessons'  , 'url' => '/lessons'          , 'icon' => 'fas fa-pencil-ruler'           , 'parent_name' => null      , 'order' => 2 , 'menu' => true],
            [ 'code' => 'lessons.basic'    , 'title' => 'Basic'    , 'url' => '/lessons/basic'    , 'icon' => 'far fa-star text-teal'   , 'parent_name' => 'lessons' , 'order' => 0 , 'menu' => true],
            [ 'code' => 'lessons.medium'   , 'title' => 'Medium'   , 'url' => '/lessons/medium'   , 'icon' => 'fas fa-star-half text-blue'   , 'parent_name' => 'lessons' , 'order' => 1 , 'menu' => true],
            [ 'code' => 'lessons.advanced' , 'title' => 'Advanced' , 'url' => '/lessons/advanced' , 'icon' => 'fas fa-star text-orange' , 'parent_name' => 'lessons' , 'order' => 2 , 'menu' => true],

            [ 'code' => 'user', 'title' => 'Users', 'url' => '/user', 'icon' => 'fas fa-user-friends', 'parent_name' => null, 'order' => 0, 'menu' => true],
            
        ]);
    }
}
