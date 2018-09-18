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
                'name' => 'HR Manager', 
            ),
            array(
                'code' => 'hr1', 
                'name' => 'HR Associates 1', 
            ),
            array(
                'code' => 'hr2', 
                'name' => 'HR associates 2', 
            ),
            array(
                'code' => 'hr3', 
                'name' => 'HR Associates 3', 
            ),
            array(
                'code' => 'hr4', 
                'name' => 'HR Associates 4', 
            ),
            array(
                'code' => 'hr5', 
                'name' => 'HR Associates 5', 
            ),
            array(
                'code' => 'om', 
                'name' => 'Operations Manager', 
            ),
            array(
                'code' => 'tl', 
                'name' => 'Team Leader', 
            ),
            array(
                'code' => 'agent', 
                'name' => 'Agent', 
            )
        );

        AccessLevel::insert($data);
    }
}
