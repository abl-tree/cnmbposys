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
            'firstname' => 'Maricel',
            'lastname' => 'Ramales', 
            'middlename' => 'Obsiana', 
            'birthdate' => '12/09/1986', 
            'address' => 'Carnation Street, Buhangin, Davao City',
            'gender' => 'Female', 
            'status' => 'Active',
            'hired_date' => '02/09/2015',
            'excel_hash' =>strtolower('maricelramalesobsiana'),
            ],
            [
            'firstname' => 'Kenneth',
            'lastname' => 'Pulvera', 
            'middlename' => 'Llanos', 
            'birthdate' => '03/07/2087', 
            'address' => 'Jerome Agdao Davao City',
            'gender' => 'Male', 
            'status' => 'Active',
            'hired_date' => '01/12/2017',
            'excel_hash' =>strtolower('kennethpulverallanos'),
            ],
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
