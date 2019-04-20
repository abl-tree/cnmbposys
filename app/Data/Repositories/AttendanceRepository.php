<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 10/04/2019
 * Time: 9:36 PM
 */

namespace App\Data\Repositories;
ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AgentSchedule;
use App\Data\Models\Attendance;
use App\User;
use App\Data\Models\UserInfo;
use App\Data\Repositories\BaseRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Repositories\ExcelRepository;
use App\Services\ExcelDateService;
use App\Data\Repositories\LogsRepository;

class AttendanceRepository extends BaseRepository
{
    protected
        $agent_schedule,
        $attendance_repo,
        $logs,
        $excel_date,
        $user_info,
        $user;
    public function __construct(
        AgentSchedule $agentSchedule,
        Attendance $attendance,
        ExcelDateService $excelDate,
        LogsRepository $logs_repo,
        User $user,
        UserInfo $userInfo
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->attendance_repo = $attendance;
        $this->excel_date = $excelDate;
        $this->user_info = $userInfo;
        $this->logs = $logs_repo;
    }

    public function bulkScheduleInsertion($data = []){
        $failed = [];
        if (isset ($data[0]['auth_id'])) {
            $auth_id = $data[0]['auth_id'];
            unset($data[0]);
        }
        if (isset ($data['auth_id'])){
            $auth_id = $data['auth_id'];
            unset($data['auth_id']);
        }
        if(!isset($auth_id)){
            return $this->setResponse([
                'code'  => 500,
                'title' => "No user was logged in.",
            ]);
        }
        foreach($data as $key => $save){
            $save['auth_id'] = $auth_id;
            $result = $this->defineAgentAttendance($save);

            if($result->code != 200){
                $failed[] = $save;
                unset($data[$key]);
            }
        }

        $result->meta = [
            'total_success' => count($data),
            'total_failed' => count($failed)
        ];

        $result->parameters = [
            'success' => $data,
            'failed' => $failed
        ];

        return $result;
    }

    public function defineAgentAttendance($data = [])
    {
        $auth_id = null;
        if(array_key_exists('auth_id', $data)) {
           $auth_id = $data['auth_id'];
        }
        unset($data['auth_id']);
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {

                if(isset($data['email'])){
                    $user = $this->user->where('email', $data['email'])->first();
                    if(isset($user->id)){
                        $schedule = $this->agent_schedule->where('user_id', $user->id)->first();
                            if (isset($schedule->id)) {
                                $data['schedule_id'] = $schedule->id;
                            }
                    }
                }

                if(!isset($data['schedule_id'])){
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => "User ID is not set. | Email is not registered",
                        'parameters' => $data
                    ]);
                }
            }

            // data validation
            if (!isset($data['time_in'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Time in is not set.",
                ]);
            }

            // data validation
            if (!isset($data['time_out'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Time out is not set.",
                ]);
            }

        }

        // existence check

        if (isset($data['schedule_id'])) {
            if (!$this->agent_schedule->find($data['schedule_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Schedule ID is not available.",
                ]);
            }
        }

        // insertion
        if (isset($data['id'])) {
            $attendance = $this->attendance_repo->find($data['id']);
        } else {
            $attendance = $this->attendance_repo->init($this->attendance_repo->pullFillable($data));
        }

        if (!$attendance->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $attendance->errors(),
                ],
            ]);
        }
        if ($auth_id != null) {
            $logged_in_user = $this->user->find($auth_id);
            $timed_in_user = $this->user->find($user->id);
            $message =  "Successfully created an attendance for ".$timed_in_user->full_name."[".$timed_in_user->access->name."]"." by ".$logged_in_user->full_name."[".$logged_in_user->access->name."].";
        }
        else{
            $logged_in_user = $this->user->find($user->id);
            $message =  "Successfully created an attendance for ".$logged_in_user->full_name."[".$logged_in_user->access->name."].";
        }
        if (!$logged_in_user) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "User ID is not available.",
            ]);
        }
        $logged_data = [
            "user_id" => $auth_id != null ? $auth_id : $user->id,
            "action" => "POST",
            "affected_data" =>$message
        ];
        $this->logs->logsInputCheck($logged_data);
        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an attendance.",
            "parameters" => $attendance,
        ]);

    }

    public function deleteAgentAttendance($data = [])
    {
        $record = $this->attendance_repo->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Agent attendance not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting agent attendance was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'attendance_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Agent attendance deleted",
            "description" => "An agent attendace was deleted.",
            "parameters"        => [
                "attendance_id" => $data['id']
            ]
        ]);

    }

    public function excelData($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $arr = [];
        $firstPage  = $excel[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            if($x+1 < count($firstPage))
            {
                $arr[] = array(
                    "email" => $firstPage[$x+1][0],
                    "time_in" => $this->excel_date->excelDateToPHPDate($firstPage[$x+1][1]),
                    "time_out" => $this->excel_date->excelDateToPHPDate($firstPage[$x+1][2]),
                );

            }

        }

        $arr['auth_id'] = $data['auth_id'];
        $result = $this->bulkScheduleInsertion($arr);
        return $result;

    }

    public function fetchAgentAttendance($data = [])
    {
        $meta_index = "attendances";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "attendances";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['schedule_id'] = $data['id'];

        }

        $count_data = $data;

        $data['relations'] = ['schedule.user_info','schedule.title'];
        $result     = $this->fetchGeneric($data, $this->attendance_repo);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No agent attendances are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->attendance_repo->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved agent attendance",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchAgentAttendance($data)
    {
        $result = $this->attendance_repo;

        $meta_index = "attendances";
        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ['schedule.user_info', 'schedule.title'];

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agent attendances are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->attendance_repo->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched agent attendances",
            "meta" => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}