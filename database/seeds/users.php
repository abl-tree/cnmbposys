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
                    'name' => 'HR God',
                    'email' => 'test@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 1,
            ),
            array(
                    'name' => 'HR Associate 1',
                    'email' => 'hr1@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 2,
            ),
            array(
                    'name' => 'HR Associate 2',
                    'email' => 'hr2@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 3,
            ),
            array(
                    'name' => 'HR Associate 3',
                    'email' => 'hr3@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 4,
            ),
            array(
                    'name' => 'HR Associate 4',
                    'email' => 'hr4@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 5,
            ),
            array(
                    'name' => 'HR Associate 5',
                    'email' => 'hr5@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 6,
            ),
            array(
                    'name' => 'Operations Manager',
                    'email' => 'om@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 7,
            ),
            array(
                    'name' => 'Team Leader',
                    'email' => 'tl@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 8,
            ),
            array(
                    'name' => 'Agent One',
                    'email' => 'aone@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 9,
            ),
            array(
                    'name' => 'Agent Two',
                    'email' => 'atwo@gmail.com',
                    'password' => bcrypt('123456'),
                    'access_id' => 9,
            ),
            array(
                    'name' => 'Agent Three',
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
