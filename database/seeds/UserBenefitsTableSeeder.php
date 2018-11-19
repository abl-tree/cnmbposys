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
        $data[] = ['user_info_id' => 1,'benefit_id' => 1,'id_number' =>3408447990,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 2,'id_number' =>160504211034,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 3,'id_number' =>105001637585,];
        $data[] = ['user_info_id' => 1,'benefit_id' => 4,'id_number' =>250305012,];
        
        $data[] = ['user_info_id' => 2,'benefit_id' => 1,'id_number' =>930282875,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 2,'id_number' =>160503517083,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 3,'id_number' =>121045377849,];
        $data[] = ['user_info_id' => 2,'benefit_id' => 4,'id_number' =>264573414,];

        foreach ($data as $key => $value) {
            UserBenefit::create($value);
        }
    }
}
