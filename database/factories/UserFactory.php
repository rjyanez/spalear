<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
	// $roles = ['AD','SC','TE','ST'];
    return [
        'name'           => $faker->firstName.' '.$faker->lastName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => Hash::make('123456'),
        'remember_token' => str_random(10),
        'country_code'   => 'VE',
        'time_zone_id'   => 418,
        'description'    => $faker->paragraph,
        // 'rol_code'       => $roles[rand(0,3)],
        'online'		 => rand(0,1)
    ];
});
