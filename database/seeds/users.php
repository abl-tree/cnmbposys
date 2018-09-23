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
            'email' => 'test@gmail.com',
            'password' => '123456',
            'access_id' => 1,
            ],
            [
            'uid'=>2,
            'email' => 'testhra@gmail.com',
            'password' => '123456',
            'access_id' => 2,
            ]
        ];
       
        foreach($data as $datum){
            User::create($datum);
        }
    }
}
