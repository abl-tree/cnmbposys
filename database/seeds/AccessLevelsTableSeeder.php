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
                'parent'=>null,
            ),
            array(
                'code' => 'hrm', 
                'name' => 'HR Manager',
                'parent' => 1,
            ),
            array(
                'code' => 'om', 
                'name' => 'Operations Manager', 
                'parent' => 1,
            ),
            array(
                'code' => 'accm', 
                'name' => 'Accounts Manager', 
                'parent' => 1,
            ),
            array(
                'code' => 'rtam', 
                'name' => 'RTA Manager', 
                'parent' => 1,
            ),
            array(
                'code' => 'tqm', 
                'name' => 'TQ Manager', 
                'parent' => 1,
            ),
            array(
                'code' => 'hrs', 
                'name' => 'HR Specialist', 
                'parent' => 2,
            ),
            array(
                'code' => 'hra', 
                'name' => 'HR Associate', 
                'parent' => 2,
            ),
            array(
                'code' => 'tl', 
                'name' => 'Team Leader', 
                'parent' => 3,
            ),
            array(
                'code' => 'rtaa', 
                'name' => 'RTA Analyst', 
                'parent' => 5,
            ),
            array(
                'code' => 'qa', 
                'name' => 'Quality Assurance', 
                'parent' => 6,
            ),
            array(
                'code' => 'agent', 
                'name' => 'Agent', 
                'parent' => 9,
            ),
        );

        AccessLevel::insert($data);
    }
}
