<?php

use Illuminate\Database\Seeder;
use App\UserInfo;
use Carbon\Carbon;

class UserInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            'uid' => 1, 
            'firstname' => 'hadji',
            'middlename' => 'cool',
            'lastname' => 'kouceyla',
            'birthdate' => Carbon::now(),
            'gender' => 'M', 
            'contact_number' => '0000000000',
            'salary_rate' => 500,
            'address' => 'Davao City, Davao del Sur, 8000, Philippines',
        );

        UserInfo::create($data);
    }
}
