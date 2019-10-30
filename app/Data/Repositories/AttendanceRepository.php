<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AgentSchedule;
use App\Data\Models\Attendance;
use App\Data\Models\UserInfo;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\ExcelRepository;
use App\Data\Repositories\LogsRepository;
use App\Services\ExcelDateService;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;

class AttendanceRepository extends BaseRepository
{
    protected $agent_schedule,
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

    public function bulkScheduleInsertion($data = [])
    {
        $failed = [];
        if (isset($data[0]['auth_id'])) {
            $auth_id = $data[0]['auth_id'];
            unset($data[0]);
        }
        if (isset($data['auth_id'])) {
            $auth_id = $data['auth_id'];
            unset($data['auth_id']);
        }
        if (!isset($auth_id)) {
            return $this->setResponse([
                'code' => 500,
                'title' => "No user was logged in.",
            ]);
        }
        foreach ($data as $key => $save) {
            $save['auth_id'] = $auth_id;
            $result = $this->defineAgentAttendance($save);

            if ($result->code != 200) {
                $failed[] = $save;
                unset($data[$key]);
            }
        }

        $result->meta = [
            'total_success' => count($data),
            'total_failed' => count($failed),
        ];

        $result->parameters = [
            'success' => $data,
            'failed' => $failed,
        ];

        return $result;
    }

    public function defineAgentAttendance($data = [])
    {
        $auth_id = null;
        if (array_key_exists('auth_id', $data)) {
            $auth_id = $data['auth_id'];
        }
        unset($data['auth_id']);
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {

                if (isset($data['email'])) {
                    $user = $this->user->where('email', $data['email'])->first();
                    if (isset($user->id)) {
                        $schedule = $this->agent_schedule->where('user_id', $user->id)->first();
                        if (isset($schedule->id)) {
                            $data['schedule_id'] = $schedule->id;
                        }
                    }
                }

                if (!isset($data['schedule_id'])) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "User ID is not set. | Email is not registered",
                        'parameters' => $data,
                    ]);
                }
            }

            if (isset($data['user_id'])) {
                $user = $this->user->where('id', $data['user_id'])->first();
                if (isset($user->id)) {
                    $schedule = $this->agent_schedule->where('user_id', $user->id);
                    if (isset($schedule->id)) {
                        $data['schedule_id'] = $schedule->id;
                    }
                }
            }

            // data validation
            if (!isset($data['time_in'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Time in is not set.",
                    'meta' => [
                        'agent_schedule' => $schedule
                    ]
                ]);
            }

            // data validation
            // if (!isset($data['time_out'])) {
            //     return $this->setResponse([
            //         'code'  => 500,
            //         'title' => "Time out is not set.",
            //     ]);
            // }

        }

        // existence check

        if (isset($data['schedule_id'])) {
            if (!$this->agent_schedule->find($data['schedule_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Schedule ID is not available.",
                ]);
            }
        }

        if (isset($data['user_id'])) {
            $user = $this->user->where('id', $data['user_id'])->first();
            if (isset($user->id)) {
                $schedule = $this->agent_schedule->where('user_id', $user->id);
                if (isset($schedule->id)) {
                    $data['schedule_id'] = $schedule->id;
                }
            }
        }
        // insertion
        if (isset($data['id'])) {
            $attendance = $this->attendance_repo->find($data['id']);
            $schedule = $this->agent_schedule->find($data['schedule_id']);
        } else {
            $attendance = $this->attendance_repo->init($this->attendance_repo->pullFillable($data));
            $schedule = $this->agent_schedule->find($data['schedule_id']);
        }

        if (!$attendance) {
            return $this->setResponse([
                'code' => 404,
                'title' => "Attendance not found.",
                'meta' => [
                    'agent_schedule' => $schedule
                ]
            ]);
        }

        if (!$attendance->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $attendance->errors(),
                    'agent_schedule' => $schedule
                ],
            ]);
        }
        if ($auth_id != null) {
            $logged_in_user = $this->user->find($auth_id);
            // $timed_in_user = $attendance ? $this->user->find($attendance->id) : $this->user->find($user->id);
            // $message = "Successfully created an attendance for " . $timed_in_user->full_name . "[" . $timed_in_user->access->name . "]" . " by " . $logged_in_user->full_name . "[" . $logged_in_user->access->name . "].";
        } else {
            // $logged_in_user = $attendance ? $this->user->find($attendance->id) : $this->user->find($user->id);
            // $message = "Successfully created an attendance for " . $logged_in_user->full_name . "[" . $logged_in_user->access->name . "].";
        }
        if (!$logged_in_user) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is not available.",
            ]);
        }
        // $logged_data = [
        //     "user_id" => $auth_id != null ? $auth_id : $attendance ? $attendance->id : $user->id,
        //     "action" => "POST",
        //     "affected_data" => $message,
        // ];
        // $this->logs->logsInputCheck($logged_data);

        $response = $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an attendance.",
            'meta' => [
                'agent_schedule' => $schedule
            ],
            "parameters" => $attendance,
        ]);
        //pusher
        // event(new StartWork($response));
        return $response;

    }

    public function deleteAgentAttendance($data = [])
    {
        $record = $this->attendance_repo->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Agent attendance not found",
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code" => 500,
                "message" => "Deleting agent attendance was not successful.",
                "meta" => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'attendance_id' => $data['id'],
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Agent attendance deleted",
            "description" => "An agent attendace was deleted.",
            "parameters" => [
                "attendance_id" => $data['id'],
            ],
        ]);

    }

    public function excelData($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $arr = [];
        $firstPage = $excel[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            if ($x + 1 < count($firstPage)) {
                $arr[] = array(
                    "email" => $firstPage[$x + 1][0],
                    "time_in" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 1][1]),
                    "time_out" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 1][2]),
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
        $result = $this->attendance_repo;
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "attendances";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['schedule_id'] = $data['id'];

        }

        if(isset($data['time_out']) && $data['time_out'] === 'false') {

            $result = $result->where(function($q) {
                $q->whereNull('time_out')->orWhereNotNull('time_out_by');
            });

        }

        $count_data = $data;

        $data['relations'] = ['schedule.user_info', 'schedule.title'];
        $result = $this->fetchGeneric($data, $result);

        if (!$result) {
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
            "title" => "Successfully retrieved agent attendance",
            "meta" => [
                $meta_index => $result,
                "count" => count($result),
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
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function timeIn($data)
    {

        if(!isset($data['schedule_id'])){
            return $this->setResponse([
                "code" => 500,
                "title" => "Schedule ID not set",
                "parameters" => $data
            ]);
        }

        $schedule = $this->agent_schedule->find($data['schedule_id']);

        if(!$schedule){
            return $this->setResponse([
                "code" => 500,
                "title" => "Unknown schedule ID.",
                "parameters" => $data
            ]);
        }

        $check_attendance = $this->attendance_repo->where('schedule_id',$data['schedule_id'])->first();

        if($check_attendance){
            return $this->setResponse([
                "code" => 500,
                "title" => "You already have existing time in stamp.",
                "parameters" => $data
            ]);
        }

        if(!$check_attendance){
            // allow timein 2hours before the scheduled time incident
            //  deduct 2hours from scheduled timein
            $scheduled_timein = Carbon::parse($schedule->time_in);
            $allowed_timein = $scheduled_timein->subHours(2);
            $now = Carbon::now();
            if(!$allowed_timein->isAfter($now)){
                return $this->setResponse([
                    "code" => 422,
                    "title" => "You are only allowed to time-in 2 hours before the schedule.",
                ]);
            }


            $time_in = $this->attendance_repo->init($this->attendance_repo->pullFillable($data));
            $data['time_in'] = Carbon::now();
            if(!$time_in->save($data)){
                return $this->setResponse([
                    "code" => 500,
                    "title" => "There's a problem with your input data.",
                    "parameters" => $data
                ]);
            }

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully timed in at ".$data['time_in'].".",
                'meta' => [
                    'agent_schedule' => $schedule
                ],
                "parameters" => $data,
            ]);
        }
    }

    public function timeOut($data)
    {
        $auth = Auth::user();

        $isRta = $auth->accesslevel()->first()->code === 'rtamanager' ||$auth->accesslevel()->first()->code === 'rtasupervisor' ||$auth->accesslevel()->first()->code === 'rtaanalyst'  ? true : false;

        // validate if request doesn't have attendance_id then throw error
        if(!isset($data['attendance_id'])){
            return $this->setResponse([
                "code" => 500,
                "title" => "Attendance ID not set",
                "parameters" => $data
            ]);
        }

        //  find attendance by requested attendance_id
        $attendance = $this->attendance_repo->find($data['attendance_id']);

        // validate if find attendance is null then throw error
        if(!$attendance){
            return $this->setResponse([
                "code" => 500,
                "title" => "You don't have timein stamp.",
                "parameters" => $data
            ]);
        }

        // validate if attendance have time_out value then throw error
        if($attendance->time_out){
            return $this->setResponse([
                "code" => 500,
                "title" => "You already have timeout stamp.",
                "parameters" => $data
            ]);
        }

        // find schedule for meta response
        $schedule = $this->agent_schedule->find($attendance->schedule_id);

        // define time_out with current date and time
        if($isRta) {
            if(!isset($data['time_out'])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Timeout parameter not set.",
                    "parameters" => $data
                ]);
            }

            if(!Carbon::parse($attendance->time_in)->lt(Carbon::parse($data['time_out']))) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Timeout parameter should be greater than time-in stamp ".$attendance->time_in.".",
                    "parameters" => $data
                ]);
            }

            $data['time_out_by'] = $auth->id;
        } else {
            $data['time_out'] = Carbon::now();
        }

        // if not saved throw error
        if(!$attendance->save($data)){
            return $this->setResponse([
                "code" => 500,
                "title" => "There's a problem with your input data.",
                "parameters" => $data
            ]);
        }

        // if saved thow success error
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully timed out at ".$data['time_out'].".",
            'meta' => [
                'agent_schedule' => $schedule
            ],
            "parameters" => $data,
        ]);

    }

    public function timeOutRemove($data)
    {
        $auth = Auth::user();

        $isRta = $auth->accesslevel()->first()->code === 'rtamanager' || $auth->accesslevel()->first()->code === 'rtasupervisor' || $auth->accesslevel()->first()->code === 'rtaanalyst' ? true : false;

        // validate if request doesn't have attendance_id then throw error
        if(!isset($data['attendance_id'])){
            return $this->setResponse([
                "code" => 500,
                "title" => "Attendance ID not set",
                "parameters" => $data
            ]);
        }

        //  find attendance by requested attendance_id
        $attendance = $this->attendance_repo->find($data['attendance_id']);

        // validate if attendance exists
        if(!$attendance){
            return $this->setResponse([
                "code" => 500,
                "title" => "Attendance does not exist.",
                "parameters" => $data
            ]);
        }

        // find schedule for meta response
        $schedule = $this->agent_schedule->find($attendance->schedule_id);

        // define time_out with current date and time
        if($isRta) {

            $data['time_out'] = null;

            $data['time_out_by'] = null;

        } else {

            return $this->setResponse([
                "code" => 500,
                "title" => "You are not allowed to remove timeout stamp",
                "parameters" => $data
            ]);

        }

        // if not saved throw error
        if(!$attendance->save($data)){
            return $this->setResponse([
                "code" => 500,
                "title" => "There's a problem with your input data.",
                "parameters" => $data
            ]);
        }

        // if saved thow success error
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully removed timeout.",
            'meta' => [
                'agent_schedule' => $schedule
            ],
            "parameters" => $data,
        ]);

    }

}