<?php

use Illuminate\Database\Seeder;
use App\AccessLevelHierarchy;

class AccessLevelHierarchiesTableSeeder extends Seeder
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
                'parent_id' => null, 
                'child_id' => 1, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 2, 
            ),
            array(
                'parent_id' => 2, 
                'child_id' => 3, 
            ),
            array(
                'parent_id' => 2, 
                'child_id' => 4, 
            ),
            array(
                'parent_id' => 1, 
                'child_id' => 5, 
            ),
            array(
                'parent_id' => 5, 
                'child_id' => 6, 
            ),
            array(
                'parent_id' => 5, 
                'child_id' => 7, 
            ),
            array(
                'parent_id' => 5, 
                'child_id' => 8, 
            ),
            array(
                'parent_id' => 6, 
                'child_id' => 9, 
            ),
        );

        foreach ($data as $key => $value) {
            AccessLevelHierarchy::create($value);
        }
    }
}
