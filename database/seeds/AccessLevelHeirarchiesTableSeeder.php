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
            array(
                'parent_id' => 1, 
                'child_id' => 3, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 4, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 5, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 6, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 7, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 8, 
            ),
            array(
                'parent_id' => 7, 
                'child_id' => 8, 
            ),
            array(
                'parent_id' => 8, 
                'child_id' => 9, 
            ),
        );

        foreach ($data as $key => $value) {
            AccessLevelHeirarchy::create($value);
        }
    }
}
