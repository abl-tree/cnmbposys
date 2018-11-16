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
            'firstname' => 'Admin',
            'lastname' => 'Admin', 
            'middlename' => 'Admin', 
            'birthdate' => '12/12/2012', 
            'address' => 'Davao City',
            'gender' => 'Female', 
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>strtolower('AdminAdminAdmin'),
            ],
            [
            'firstname' => 'F.HRM',
            'lastname' => 'L.HRM', 
            'middlename' => 'M.HRM', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>strtolower('F.HRMM.HRML.HRM'),
            ],
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
