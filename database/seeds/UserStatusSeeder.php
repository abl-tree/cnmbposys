<?php

use Illuminate\Database\Seeder;
use App\Data\Models\UserStatus;
class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = ['status' => "active",'type' => "New Hired",'description' =>"Newly Hired User",];
        $data[] = ['status' => "active",'type' => "Active",'description' =>"Active User",];
        $data[] = ['status' => "inactive",'type' => "Suspended",'description' =>"Suspended User",];
        $data[] = ['status' => "inactive",'type' => "Terminated",'description' =>"Terminated User",];
        $data[] = ['status' => "inactive",'type' => "Resigned",'description' =>"Resigned User",];
        
        foreach ($data as $key => $value) {
            UserStatus::create($value);
        }
    }
}
