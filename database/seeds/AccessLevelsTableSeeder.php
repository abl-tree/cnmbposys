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
                'code' => 'hra', 
                'name' => 'HR Associates', 
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
