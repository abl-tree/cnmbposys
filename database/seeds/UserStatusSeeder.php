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
        $data[] = ['status' => "active",'type' => "New Hired",'description' =>"Newly Hired Users",];
        $data[] = ['status' => "active",'type' => "Active",'description' =>"Active Users",];
        $data[] = ['status' => "inactive",'type' => "Suspended",'description' =>"Suspended Users",];
        $data[] = ['status' => "inactive",'type' => "Terminated",'description' =>"Terminated Users",];
        $data[] = ['status' => "inactive",'type' => "Resigned",'description' =>"Resigned Users",];
        
        foreach ($data as $key => $value) {
            UserStatus::create($value);
        }
    }
}
