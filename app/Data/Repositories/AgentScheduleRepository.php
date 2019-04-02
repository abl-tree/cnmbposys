<?php
/**
 * Created by PhpStorm.
 * User: Janrey
 * Date: 30/10/2018
 * Time: 2:12 PM
 */

namespace App\Data\Repositories;

use App\Data\Models\AgentSchedule;
use App\Data\Models\UserInfo;
use App\Data\Models\EventTitle;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Repositories\ExcelRepository;
use App\Data\Repositories\BaseRepository;

class AgentScheduleRepository extends BaseRepository
{

    protected 
        $agent_schedule,
        $user,
        $user_info,
        $event_title;

    public function __construct(
        AgentSchedule $agentSchedule,
        User $user,
        UserInfo $userInfo,
        EventTitle $eventTitle
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->user_info = $userInfo;
        $this->event_title = $eventTitle;
    }

    public function excelData($data)
    {
        $data = Excel::toArray(new ExcelRepository, $data);
        $filteredData1 = [];
        $filteredData2 = [];
        $filteredData3 = [];
        $firstPage  = $data[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            if(isset($firstPage[$x+3])){
                if($firstPage[$x+3][1] != null)
                {
                    $filteredData1[] = array(
                        "email" => $firstPage[$x+3][1],
                        "title" => 'work schedule',
                        "start_event" => $firstPage[$x+3][4],
                        "end_event" => $firstPage[$x+3][5],
                    );
                }
            }
        }
        
        return $this->setResponse([
            "code"        => 200,
            "title"       => "Conversion success.",
            "description" => "Successfully converted excel data into formatted data.",
            "meta"        => [
                "schedules" => $filteredData1 ,
            ],
        ]);
    }

    public function bulkScheduleInsertion($data = []){
        foreach($data as $save){ 
           $result = $this->defineAgentSchedule($save);

           if($result->code != 200){
               return $result;
           }
        }

        $result->parameters = $data;
        return $result;
    }

    public function defineAgentSchedule($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {
                
                if(isset($data['email'])){
                    $user = $this->user->where('email', $data['email'])->first();
                    $data['user_id'] = $user->id;
                }else{
                    return $this->setResponse([ 
                        'code'  => 500,
                        'title' => "User ID is not set.",
                    ]);
                }    
            }

            if (!isset($data['title_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Title ID is not set.",
                ]);
            }

            if (!isset($data['start_event'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Start date is not set.",
                ]);
            }

            if (!isset($data['end_event'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "End date is not set.",
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['user_id'])) {
            if (!$this->user_info->find($data['user_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User ID is not available.",
                ]);
            }
        }

        if (isset($data['title_id'])) {
            if (!$this->event_title->find($data['title_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Title ID is not available.",
                ]);
            }
        }

        if (isset($data['id'])) {
            $does_exist = $this->agent_schedule->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Agent Schedule ID does not exist.',
                ]);
            }
        }

        // existence check

        // insertion

        if (isset($data['id'])) {
            $agent_schedule = $this->agent_schedule->find($data['id']);
        } else {
            $agent_schedule = $this->agent_schedule->init($this->agent_schedule->pullFillable($data));
        }

        if (!$agent_schedule->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $agent_schedule->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an agent schedule.",
            "parameters" => $agent_schedule,
        ]);

        // insertion

    }

    public function deleteAgentSchedule($data = [])
    {
        $record = $this->agent_schedule->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Agent schedule not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting agent schedule was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'schedule_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Agent schedule deleted",
            "description" => "An agent schedule was deleted.",
            "parameters"        => [
                "schedule_id" => $data['id']
            ]
        ]);

    }

    public function fetchAgentSchedule($data = [])
    {
        $meta_index = "agent_schedules";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "agent_schedule";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['agent_schedule_id'] = $data['id'];

        }

        $count_data = $data;

        $data['relations'] = ["user_info", 'title'];

        $result     = $this->fetchGeneric($data, $this->agent_schedule);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No agent schedules are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->agent_schedule->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved agent schedules",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function fetchAllAgentsWithSchedule($data = [])
    {
        $meta_index = "agents";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "agent";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
                [
                    "target"   => "access_id",
                    "operator" => "=",
                    "value"    => '17',
                ],
            ];

            $parameters['agent_id'] = $data['id'];

        } else {
            
            $data['where']  = [
                [
                    "target"   => "access_id",
                    "operator" => "=",
                    "value"    => '17',
                ],
            ];
        }

        $count_data = $data;

        $data['relations'] = ['info', 'schedule.title'];

        $result     = $this->fetchGeneric($data, $this->user);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No agents with schedules are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved agent schedules",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchAgentSchedule($data)
    {
        $result = $this->agent_schedule;

        $meta_index = "agent_schedules";
        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ['user_info', 'title'];

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agent schedules are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->agent_schedule->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched agent schedules",
            "meta" => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }


}
