<?php

use Illuminate\Database\Seeder;
use App\Data\Models\UserInfo;
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
            'middlename' => 'Ramales', 
            'lastname' => 'Obsiana', 
            'birthdate' => '12/9/1986', 
            'address' => 'Carnation Street, Buhangin, Davao City',
            'gender' => 'Female', 
            'status' => 'active',
            'hired_date' => '2/9/2015',
            'excel_hash' =>strtolower('maricelramalesobsiana'),
            ],
            [
            'firstname' => 'Kenneth',
            'middlename' => 'Pulvera', 
            'lastname' => 'Llanos', 
            'birthdate' => '3/7/1987', 
            'address' => 'Jerome Agdao Davao City',
            'gender' => 'Male', 
            'status' => 'active',
            'hired_date' => '12/1/2017',
            'excel_hash' =>strtolower('kennethpulverallanos'),
            ],
            [
            'firstname' => 'Dev',
            'middlename' => 'Elop', 
            'lastname' => 'Ment', 
            'birthdate' => '11/10/2018', 
            'address' => 'CNM Compound',
            'gender' => 'Male', 
            'status' => 'new_hired',
            'hired_date' => '11/11/2018',
            'excel_hash' =>strtolower('development'),
            ],
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
