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
            'firstname' => 'F.Admin',
            'lastname' => 'L.Admin', 
            'middlename' => 'M.Admin', 
            'birthdate' => '12/12/12', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/17',
            ],
            [
            'firstname' => 'F.HRM',
            'lastname' => 'L.HRM', 
            'middlename' => 'M.HRM', 
            'birthdate' => '12/02/12', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/17',
            ],
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
