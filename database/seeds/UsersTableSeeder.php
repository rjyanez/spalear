<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER SEQUENCE users_id_seq RESTART WITH 1'); 
        // DB::table('users')->truncate();
        
        $levels = ['BAS','MED','ADV'];
        $user = new App\User([
            'name'=>        'Admin',
            'email'=>       'admin@admin.com',
            'password'=>    Hash::make('123456'), //123456
            'country_code'=>'VE',
            'time_zone_id'=>418,
            'rol_code'=>'AD', //Administrador
        ]);

        $user->setMeta([
            'level' => $levels[rand(0,2)]
        ]);

        $user->save();

        factory(App\User::class, 50)->create()->each(function ($user) use ($levels){
            if($user->rol_code == 'ST'){
                $user->setMeta([
                    'level' => $levels[rand(0,2)]
                ]);
                $user->save();
            }
        });;
    }
}
