<?php

use Illuminate\Database\Seeder;
use App\AccessLevelHeirarchy;

class AccessLevelHeirarchiesTableSeeder extends Seeder
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
                'parent_id' => 1, 
                'child_id' => 2, 
            ),
            
        );

        foreach ($data as $key => $value) {
            AccessLevelHeirarchy::create($value);
        }
    }
}
