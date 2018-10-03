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
            'birthdate' => '12/12/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.AdminM.AdminL.Admin',
            ],
            [
            'firstname' => 'F.HRM',
            'lastname' => 'L.HRM', 
            'middlename' => 'M.HRM', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.HRMM.HRML.HRM',
            ],
            [
            'firstname' => 'F.HRS',
            'lastname' => 'L.HRS', 
            'middlename' => 'M.HRS', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Female', 
            'contact_number' => '0000000001',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.HRSM.HRSL.HRS',

            ],
            [
            'firstname' => 'F.HRA',
            'lastname' => 'L.HRA', 
            'middlename' => 'M.HRA', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.HRAM.HRAL.HRA',

            ],
            [
            'firstname' => 'F.OM',
            'lastname' => 'L.OM', 
            'middlename' => 'M.OM', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.OMM.OML.OM',

            ],
            [
            'firstname' => 'F.TL',
            'lastname' => 'L.TL', 
            'middlename' => 'M.TL', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.TLM.TLL.TL',
            ],
            [
            'firstname' => 'F.TL1',
            'lastname' => 'L.TL1', 
            'middlename' => 'M.TL1', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.TL1M.TL1L.TL1',
            ],
            [
            'firstname' => 'F.TL2',
            'lastname' => 'L.TL2', 
            'middlename' => 'M.TL2', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.TL2M.TL2L.TL2',
            ],
            [
            'firstname' => 'F.agent1',
            'lastname' => 'L.agent1', 
            'middlename' => 'M.agent1', 
            'birthdate' => '12/02/2012', 
            'address' => 'Davao City',
            'gender' => 'Male', 
            'contact_number' => '0000000000',
            'status' => 'Active',
            'hired_date' => '09/10/2017',
            'excel_hash' =>'F.agent1M.agentL.agent1',
            ],
        ];
       
        foreach($data as $datum){
            UserInfo::create($datum);
        }
    }
}
