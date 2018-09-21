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
            'email' => 'test@gmail.com',
            'password' => '123456',
            'access_id' => 1,
            ],
            [
            'email' => 'testhra@gmail.com',
            'password' => '123456',
            'access_id' => 2,
            ],
            [
            'email' => 'testom@gmail.com',
            'password' => '123456',
            'access_id' => 3,
            ],
            [
            'email' => 'testtl@gmail.com',
            'password' => '123456',
            'access_id' => 4,
            ]];
       
            foreach($data as $datum){
                User::create($datum);
            }
    }
}
