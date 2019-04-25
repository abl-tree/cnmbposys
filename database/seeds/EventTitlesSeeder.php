<?php

use Illuminate\Database\Seeder;
use App\Data\Models\EventTitle;
use Carbon\Carbon;
class EventTitlesSeeder extends Seeder
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
                'title' => 'Work', 
                'color' => '#f44e3b', 
            ),
            array(
                'title' => 'Leave', 
                'color' => '#fe9200', 
            ),
            array(
                'title' => 'Sick Leave', 
                'color' => '#fcdc00', 
            ),
            array(
                'title' => 'Maternity Leave', 
                'color' => '#dbdf00', 
            ),
            array(
                'title' => 'Paternity Leave', 
                'color' => '#a4dd00', 
            ),
            array(
                'title' => 'Bereavement Leave', 
                'color' => '#68ccca', 
            ),
            array(
                'title' => 'Leave of absence', 
                'color' => '#73d8ff', 
            ),
        );
        foreach ($data as $key => $value) {
            EventTitle::create($value);
        }
    }
}