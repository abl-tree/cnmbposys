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
        $data = [
            [
            'firstname' => 'HRM',
            'lastname' => 'kouceyla', 
            'middlename' => 'Cool', 
            'birthdate' => '12-12-12', 
            'address' => 'bario ugok',
            'gender' => 'M', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            ],
            [
            'firstname' => 'HRA',
            'lastname' => 'kouceyla', 
            'middlename' => 'Cool', 
            'birthdate' => '12-2-12', 
            'address' => 'bario ugok',
            'gender' => 'M', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            ]
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
