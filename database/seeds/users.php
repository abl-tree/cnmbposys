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
        $data = array(
            array(
                'email' => 'test@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 1,
            ),
            array(
                'email' => 'hr1@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 2,
            ),
            array(
                'email' => 'hr2@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 3,
            ),
            array(
                'email' => 'hr3@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 4,
            ),
            array(
                'email' => 'hr4@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 5,
            ),
            array(
                'email' => 'hr5@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 6,
            ),
            array(
                'email' => 'om@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 7,
            ),
            array(
                'email' => 'tl@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 8,
            ),
            array(
                'email' => 'aone@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 9,
            ),
            array(
                'email' => 'atwo@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 9,
            ),
            array(
                'email' => 'athree@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 9,
            ),
        );
        
        foreach ($data as $key => $value) {
            User::create($value);
        }
    }
}
