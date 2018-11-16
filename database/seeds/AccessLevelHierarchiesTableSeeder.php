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
        );
        foreach ($data as $key => $value) {
            AccessLevelHierarchy::create($value);
        }
    }
}
