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
            'email' => 'admin@cnm.com',
            'password' => '123456',
            'access_id' => 1,
            ],
            [
            'uid'=>2,
            'email' => 'test@gmail.com',
            'password' => '123456',
            'access_id' => 2,
            ],
            [
            'uid'=>3,
            'email' => 'hrs@cnm.com',
            'password' => '123456',
            'access_id' => 7,
            ],
            [
            'uid'=>4,
            'email' => 'hra@cnm.com',
            'password' => '123456',
            'access_id' => 8,
            ],
            [
            'uid'=>5,
            'email' => 'om@cnm.com',
            'password' => '123456',
            'access_id' => 3,
            ],
            [
            'uid'=>6,
            'email' => 'tl@cnm.com',
            'password' => '123456',
            'access_id' => 9,
            ],
            [
            'uid'=>7,
            'email' => 'tl1@cnm.com',
            'password' => '123456',
            'access_id' => 9,
            ],
            [
            'uid'=>8,
            'email' => 'tl2@cnm.com',
            'password' => '123456',
            'access_id' => 9,
            ],
            [
            'uid'=>9,
            'email' => 'agent@cnm.com',
            'password' => '123456',
            'access_id' => 12,
            ],
        ];
       
        foreach($data as $datum){
            User::create($datum);
        }
    }
}