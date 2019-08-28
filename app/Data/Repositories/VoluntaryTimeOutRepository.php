<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AgentSchedule;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use App\Data\Models\LeaveCredit;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use Validator;

class VoluntaryTimeOutRepository extends BaseRepository
{

    protected $agent_schedule,
        $user,
        $logs,
        $leave_credit,
        $notification_repo;

    public function __construct(
        AgentSchedule $agentSchedule,
        User $user,
        LeaveCredit $leaveCredit,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->leave_credit = $leaveCredit;
        $this->logs = $logs_repo;
        $this->notification_repo = $notificationRepository;
    }

    public function defineVto($schedule_id, $data = [])
    {
        // data validation

        $validator = Validator::make($data,[
            'timestamp' => 'required|date|date_format:Y-m-d H:i:s'
        ], [
            'timestamp.date_format' => "The timestamp does not match the format YYYY-MM-DD HH:MM:SS."
        ]);

        $auth_user = Auth::user();
        $auth_id = $auth_user->id;
        $title = "";

        if($validator->fails()) {
            $errors = $validator->errors();
            $errorText = "";

            foreach ($errors->all() as $message) {
                $errorText .= $message;
            }

            return $this->setResponse([
                'code' => 500,
                'title' => $errorText,
                'parameters' => $data
            ]);
        }

        $timestamp = Carbon::parse($data['timestamp']);
        
        if ($auth_user->access->code === 'representative_op') {
            return $this->setResponse([
                'code' => 500,
                'title' => "Unauthorized account.",
            ]);
        }

        // data validation

        // existence check

        $agent_schedule = $this->agent_schedule->find($schedule_id);

        if (!$agent_schedule) {
            return $this->setResponse([
                'code' => 500,
                'title' => 'Agent Schedule ID does not exist.',
            ]);
        }

        if($agent_schedule->overtime_id) {
            return $this->setResponse([
                'code' => 500,
                'title' => 'Overtime schedule is not allowed.',
            ]);
        }

        if(!$timestamp->between(Carbon::parse($agent_schedule->start_event), Carbon::parse($agent_schedule->end_event))) {

            return $this->setResponse([
                'code' => 500,
                'title' => 'The timestamp must be between the schedule.'
            ]);

        }

        // existence check
            
        // check available leave credits
        $leave_credits = $this->leave_credit
        ->where('user_id', $agent_schedule->user_id)
        ->where('leave_type', 'vacation_leave')
        ->first();

        if (!$leave_credits) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Employee does not have vacation leave credits.",
            ]);
        }

        // update

        if($agent_schedule->vto_at) {
    
            $total_vto_hrs = (float) Carbon::parse($agent_schedule->end_event)->diffInSeconds($agent_schedule->vto_at) / 3600;   
            $total_vto_hrs = number_format($total_vto_hrs, 2);

            $data = [
                'vto_at' => null
            ];

            //revert leave credits
            $leave_credits->update([
                'value' => (float) $leave_credits->value + $total_vto_hrs,
            ]);

            $title = "Successfully removed a VTO.";

        } else {
    
            $total_vto_hrs = (float) Carbon::parse($agent_schedule->end_event)->diffInSeconds($timestamp) / 3600;   
            $total_vto_hrs = number_format($total_vto_hrs, 2);
    
            if ($leave_credits->value < $total_vto_hrs) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Employee does not have enough leave credits.",
                    'parameters' => [
                        'credits' => [
                            'available' => $leave_credits->value,
                            'needed' => $total_vto_hrs,
                        ],
                    ],
                ]);
            }

            //update leave credits
            $leave_credits->update([
                'value' => $leave_credits->value - $total_vto_hrs,
            ]);

            $data = [
                'vto_at' => isset($timestamp) ? $timestamp : Carbon::now()
            ];

            $title = "Successfully defined a VTO at ".$timestamp;
        }

        if (!$agent_schedule->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $agent_schedule->errors(),
                ],
            ]);
        }

        if (isset($auth_id) ||
            !is_numeric($auth_id) ||
            $auth_id <= 0) {
            $logged_in_user = $this->user->find($auth_id);
            $current_employee = $this->user->find($agent_schedule->user_id);

            if (!$logged_in_user) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not available.",
                ]);
            }
            if($agent_schedule->vto_at) {
                $logged_data = [
                    "user_id" => $auth_id,
                    "action" => "POST",
                    "affected_data" => "Successfully created a VTO for " . $current_employee->full_name . "[" . $current_employee->access->name . "] on schedule " . $agent_schedule->start_event . " to " . $agent_schedule->end_event . " at " . $agent_schedule->vto_at . " by " . $logged_in_user->full_name . " [" . $logged_in_user->access->name . "].",
                ];
            } else {
                $logged_data = [
                    "user_id" => $auth_id,
                    "action" => "POST",
                    "affected_data" => "Successfully removed a VTO for " . $current_employee->full_name . "[" . $current_employee->access->name . "] on schedule " . $agent_schedule->start_event . " to " . $agent_schedule->end_event . " by " . $logged_in_user->full_name . " [" . $logged_in_user->access->name . "].",
                ];
            }
            $this->logs->logsInputCheck($logged_data);
        }

        // insertion

        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $auth_id,
            'recipient_id' => $agent_schedule->user_id,
            'type' => 'schedules.vto',
            'type_id' => $agent_schedule->id,
        ]);

        return $this->setResponse([
            "code" => 200,
            "title" => $title,
            "meta" => [
                "agent_schedule" => $agent_schedule
            ],
            "parameters" => $data
        ]);

    }

    // public function deleteAgentSchedule($data = [])
    // {
    //     $record = $this->agent_schedule->find($data['id']);

    //     if (!$record) {
    //         return $this->setResponse([
    //             "code" => 404,
    //             "title" => "Agent schedule not found",
    //         ]);
    //     }

    //     if (!$record->delete()) {
    //         return $this->setResponse([
    //             "code" => 500,
    //             "message" => "Deleting agent schedule was not successful.",
    //             "meta" => [
    //                 "errors" => $record->errors(),
    //             ],
    //             "parameters" => [
    //                 'schedule_id' => $data['id'],
    //             ],
    //         ]);
    //     }

    //     return $this->setResponse([
    //         "code" => 200,
    //         "title" => "Agent schedule deleted",
    //         "description" => "An agent schedule was deleted.",
    //         "parameters" => [
    //             "schedule_id" => $data['id'],
    //         ],
    //     ]);

    // }

    public function fetchVto($data = [])
    {
        $meta_index = "agent_schedules_vto";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['agent_schedule_id'] = $data['id'];

        }

        $count_data = $data;

        $data['relations'] = ["user_info.user", 'title'];

        $data['where_not_null'] = ['vto_at'];

        $result = $this->fetchGeneric($data, $this->agent_schedule);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No VTO is found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        if (!is_array($result)) {
            $result = [
                $result,
            ];
        }

        foreach ($result as $key => $value) {
            $value->team_leader = $value->user_info->user->team_leader;
            $value->operations_manager = $value->user_info->user->operations_manager;
            unset($value->user_info->user);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved VTO",
            "meta" => [
                $meta_index => $result,
                "count" => count($result),
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchVto($data)
    {
        $result = $this->agent_schedule;

        $meta_index = "agent_schedules";

        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ['user_info.user', 'title'];

        $data['where_not_null'] = ['vto_at'];

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'user_info.firstname';
                    $data['target'][] = 'user_info.middlename';
                    $data['target'][] = 'user_info.lastname';
                    unset($data['target'][$index]);
                }
            }
        }

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No VTO is found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->agent_schedule->getModel()));

        if (!is_array($result)) {
            $result = [
                $result,
            ];
        }

        foreach ($result as $key => $value) {
            $value->team_leader = $value->user_info->user->team_leader;
            $value->operations_manager = $value->user_info->user->operations_manager;
            unset($value->user_info->user);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched VTO",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }
}
