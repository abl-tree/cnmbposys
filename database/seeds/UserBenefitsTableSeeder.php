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
        $data = array(
            array(
                'user_info_id' => 1, 
                'benefit_id' => 1, 
                'id_number' => 11112222, 
            ), 
            array(
                'user_info_id' => 1, 
                'benefit_id' => 2, 
                'id_number' => 22223333, 
            ), 
            array(
                'user_info_id' => 1, 
                'benefit_id' => 3, 
                'id_number' => 44445555, 
            ), 
        );

        foreach ($data as $key => $value) {
            UserBenefit::create($value);
        }
    }
}
