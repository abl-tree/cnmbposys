<?php

use App\User;
use Illuminate\Database\Seeder;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
         $data = [
            [
            'uid'=>1,
            'email' => 'marieo@cnmsolutions.net',
            'password' => '123456',
            'access_id' => 1,
            'company_id'=> 0,
            'contract'=> 'Signed',
            ],
            [
            'uid'=>2,
            'email' => 'ken.llanos@cnmsolutions.net',
            'password' => '123456',
            'access_id' => 2,
            'company_id' => 350,
            'contract'=> 'Signed',
            ],
            [
            'uid'=>3,
            'email' => 'dev.team@cnmsolutions.net',
            'password' => '123456',
            'access_id' => 1,
            'company_id' => -1,
            'contract'=> 'Signed',
            ],
            
        ];
       
        foreach($data as $datum){
            User::create($datum);
        }
    }
}