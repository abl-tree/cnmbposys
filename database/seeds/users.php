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
        
        $data = [];
        
        for ($i = 1; $i <= 1 ; $i++) {
            array_push($data, [
                'email' => 'test@gmail.com',
                'password' => bcrypt('123456'),
                'access_id' => 1,
            ]);
        }
        
        User::insert($data);
    }
}
