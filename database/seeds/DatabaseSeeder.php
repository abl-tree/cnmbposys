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
        $this->call(AccessLevelsTableSeeder::class);
        $this->call(BenefitsTableSeeder::class);
        $this->call(UserInfosTableSeeder::class);
        $this->call(users::class);
        $this->call(UserBenefitsTableSeeder::class);
        $this->call(AccessLevelHierarchiesTableSeeder::class);
        $this->call(EventTitlesSeeder::class);
        $this->call(UserStatusSeeder::class);
    }
}
