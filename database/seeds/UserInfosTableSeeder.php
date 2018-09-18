<?php

use Illuminate\Database\Seeder;
use App\UserInfo;

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
            'lastname' => 'kouceyla', 
            'age' => 20, 
            'gender' => 'M', 
            'contact_number' => '0000000000',
        );

        UserInfo::create($data);
    }
}
