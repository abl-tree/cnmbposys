<?php

use Illuminate\Database\Seeder;
use App\Data\Models\Benefit;

class BenefitsTableSeeder extends Seeder
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
                'name' => 'SSS', 
            ), 
            array(
                'name' => 'Philhealth', 
            ), 
            array(
                'name' => 'Pag-ibig Funds', 
            ), 
            array(
                'name' => 'TIN', 
            ), 
        );

        foreach ($data as $key => $value) {
            Benefit::create($value);
        }
    }
}
