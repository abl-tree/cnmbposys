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
                'id_number' => 765765765, 
            ), 
            array(
                'user_info_id' => 1, 
                'benefit_id' => 2, 
                'id_number' => 789789789, 
            ), 
            array(
                'user_info_id' => 1, 
                'benefit_id' => 3, 
                'id_number' => 123235236, 
            ),
            array(
                'user_info_id' => 1, 
                'benefit_id' => 4, 
                'id_number' => 123890123, 
            ),
            array(
                'user_info_id' => 2, 
                'benefit_id' => 4, 
                'id_number' => 123123123, 
            ),
            array(
                'user_info_id' => 2, 
                'benefit_id' => 4, 
                'id_number' => 321321321, 
            ),
            array(
                'user_info_id' => 2, 
                'benefit_id' => 4, 
                'id_number' => 432432432, 
            ),
            array(
                'user_info_id' => 2, 
                'benefit_id' => 4, 
                'id_number' => 543543543, 
            )
        );

        foreach ($data as $key => $value) {
            UserBenefit::create($value);
        }
    }
}
