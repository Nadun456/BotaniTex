<?php

namespace Database\Seeders;

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
        if(!\App\Models\User::where('email', 'admin@admin.com')->first()) {
            \App\Models\User::factory(1)->create();
        }

        $this->call([
            RolesPermissionSeeder::class,
            SettingsSeeder::class,
            CountrySeeder::class,
        ]);
    }
}
