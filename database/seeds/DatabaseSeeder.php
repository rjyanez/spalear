<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CodesMetaTableSeeder::class,
            FunctionsTableSeeder::class,
            FunctionsRolesTableSeeder::class,
            CountriesTableSeeder::class,
            TimeZonesTableSeeder::class,
            UsersTableSeeder::class,
            TimeSchedulesSeeder::class,
            StudensTeachersSeeder::class,
            LessonsTableSeeder::class
        ]);
    }
}
