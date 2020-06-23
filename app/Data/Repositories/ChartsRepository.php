<?php

namespace App\Data\Repositories;


use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\AgentScheduleRepository;
use Carbon\Carbon;

class ChartsRepository extends BaseRepository
{
    protected $schedule_repo;

    public function __construct(
        AgentScheduleRepository $scheduleRepo
    ) {
        $this->schedule_repo = $scheduleRepo;
    }

    public function dailyScheduleStats($data = []){
        return $this->setResponse(
            [
                "code" => 200,
                "parameters" => $data,
                "meta" => [
                    
                ],
                "title" => "Daily Schedule Chart",
                "description" => "Data in chart structure."
            ]
        );
    }

}