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
            RolesTableSeeder::class,
            FunctionsTableSeeder::class,
            FunctionsRolesTableSeeder::class,
            CountriesTableSeeder::class,
            TimeZonesTableSeeder::class,
            UsersTableSeeder::class
        ]);
    }
}
