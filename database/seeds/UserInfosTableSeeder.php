<?php

use Illuminate\Database\Seeder;
use App\UserInfo;
use Carbon\Carbon;

class UserInfosTableSeeder extends Seeder
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
                    'uid' => 1, 
                    'firstname' => 'HR',
                    'lastname' => 'God', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 2, 
                    'firstname' => 'HR',
                    'lastname' => 'Associate 1', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 3, 
                    'firstname' => 'HR',
                    'lastname' => 'Associate 2', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 4, 
                    'firstname' => 'HR',
                    'lastname' => 'Associate 3', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 5, 
                    'firstname' => 'HR',
                    'lastname' => 'Associate 4', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 6, 
                    'firstname' => 'HR',
                    'lastname' => 'Associate 5', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 7, 
                    'firstname' => 'Operations',
                    'lastname' => 'Manager', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 8, 
                    'firstname' => 'Team',
                    'lastname' => 'Leader', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 9, 
                    'firstname' => 'Agent',
                    'lastname' => 'One', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 10, 
                    'firstname' => 'Agent',
                    'lastname' => 'Two', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
                array(
                    'uid' => 11, 
                    'firstname' => 'Agent',
                    'lastname' => 'Three', 
                    'birthdate' => Carbon::now(), 
                    'gender' => 'M', 
                    'contact_number' => '0000000000',
                    'address' => 'Davao City, Davao del Sur, 8000, Philippines',
                ),
            );

            foreach ($data as $key => $value) {
                UserInfo::create($value);
            }
    }
}
