<?php

use Illuminate\Database\Seeder;
use App\UserBenefit;

class UserBenefitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = ['user_info_id' => 1,'benefit_id' => 1,'id_number' =>1000,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 2,'id_number' =>1000,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 3,'id_number' =>1000,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 4,'id_number' =>1000,];
        
        $data[] = ['user_info_id' => 2,'benefit_id' => 1,'id_number' =>1000,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 2,'id_number' =>1000,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 3,'id_number' =>1000,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 4,'id_number' =>1000,];

        foreach ($data as $key => $value) {
            UserBenefit::create($value);
        }
    }
}
