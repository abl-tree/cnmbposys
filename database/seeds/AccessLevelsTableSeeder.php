<?php

use Illuminate\Database\Seeder;
use App\AccessLevel;

class AccessLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'code' => 'superadmin', 
                'name' => 'Admin', 
            ),
            array(
                'code' => 'hrm', 
                'name' => 'HR Manager', 
            ),
            array(
                'code' => 'om', 
                'name' => 'Operations Manager', 
            ),
            array(
                'code' => 'accm', 
                'name' => 'Accounts Manager', 
            ),
            array(
                'code' => 'rtam', 
                'name' => 'RTA Manager', 
            ),
            array(
                'code' => 'hrs', 
                'name' => 'HR Specialist', 
            ),
            array(
                'code' => 'hra', 
                'name' => 'HR Associate', 
            ),
            array(
                'code' => 'tl', 
                'name' => 'Team Leader', 
            ),
            array(
                'code' => 'rtaa', 
                'name' => 'RTA Analyst', 
            ),
            array(
                'code' => 'qa', 
                'name' => 'Quality Assurance', 
            ),
            array(
                'code' => 'agent', 
                'name' => 'Agent', 
            ),
        );

        AccessLevel::insert($data);
    }
}
