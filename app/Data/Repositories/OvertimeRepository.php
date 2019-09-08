<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AgentSchedule;
use App\Data\Models\EventTitle;
use App\Data\Models\UserInfo;
use App\Data\Models\OvertimeSchedule;
use App\Data\Models\Attendance;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use Carbon\Carbon;
use App\User;
use Auth;

class OvertimeRepository extends BaseRepository
{

    protected $agent_schedule,
    $user,
    $user_info,
    $event_title,
    $logs,
    $access_level_repo,
    $notification_repo,
    $overtime_schedule,
    $attendance;

    public function __construct(
        AgentSchedule $agentSchedule,
        User $user,
        UserInfo $userInfo,
        EventTitle $eventTitle,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository,
        OvertimeSchedule $overtimeSchedule,
        Attendance $attendance
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->user_info = $userInfo;
        $this->event_title = $eventTitle;
        $this->logs = $logs_repo;
        $this->notification_repo = $notificationRepository;
        $this->overtime_schedule = $overtimeSchedule;
        $this->attendance = $attendance;
    }

    public function bulkOvertimeScheduleInsertion($data = [])
    {
        $failed = [];
        $auth_id = Auth::id();

        if (!isset($auth_id)) {
            return $this->setResponse([
                'code' => 500,
                'title' => "No user was logged in.",
            ]);
        }

        foreach ($data as $key => $save) {
            $result = $this->defineOvertimeSchedule($save);
            // logs POST data

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

    public function defineOvertimeSchedule($data = [])
    {
        // data validation

        if (!isset($data['start_event']) && !isset($data['overtime'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Start date is not set.",
            ]);
        }

        if (!isset($data['end_event']) && !isset($data['overtime'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "End date is not set.",
            ]);
        }

        // data validation

        // existence check

        if (isset($data['id'])) {
            $does_exist = $this->overtime_schedule->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'Overtime Schedule ID does not exist.',
                ]);
            }
        }

        //Check for conflicts

        $isConflict = $this->overtime_schedule
            ->where(function($q) use ($data) {
                $q->where('start_event', '>', $data['start_event'])
                ->where([['start_event', '<', $data['end_event']], ['end_event', '>', $data['end_event']]]);
            })
            ->orWhere(function($q) use ($data) {
                $q->where('start_event', '>', $data['start_event'])
                ->where('end_event', '<', $data['end_event']);
            })
            ->orWhere(function($q) use ($data) {
                $q->where('start_event', '<', $data['start_event'])
                ->where('end_event', '>', $data['end_event']);
            })
            ->orWhere(function($q) use ($data) {
                $q->where([['start_event', '<', $data['start_event']], ['end_event', '>', $data['start_event']]])
                ->where('end_event', '<', $data['end_event']);
            })
            ->first();

        if ($isConflict) {
            return $this->setResponse([
                'code' => 500,
                'title' => 'Overtime schedule conflict.',
                'meta' => [
                    'overtime_schedule' => $isConflict
                ],
                'parameters' => $data
            ]);
        }

        // check for duplicate schedules

        $does_exist = $this->overtime_schedule
            ->where('start_event', $data['start_event'])
            ->where('end_event', $data['end_event'])
            ->first();

        // existence check

        // insertion
        if (isset($data['id'])) {
            $overtime_schedule = $this->overtime_schedule->find($data['id']);
        } else if ($does_exist) {
            $overtime_schedule = $does_exist;
        } else {
            $overtime_schedule = $this->overtime_schedule->init($this->overtime_schedule->pullFillable($data));
        }

        if (!$overtime_schedule->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $overtime_schedule->errors(),
                ],
            ]);
        }

        $auth_id = Auth::id();

        if (isset($auth_id) ||
            !is_numeric($auth_id) ||
            $auth_id <= 0) {
            $logged_in_user = $this->user->find($auth_id);

            if (!$logged_in_user) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not available.",
                ]);
            }

            $logged_data = [
                "user_id" => $auth_id,
                "action" => "POST",
                "affected_data" => "Successfully created an overtime schedule on " . $overtime_schedule->start_event . " to " . $overtime_schedule->end_event . " by " . $logged_in_user->full_name . " [" . $logged_in_user->access->name . "].",
            ];
            $this->logs->logsInputCheck($logged_data);
        }

        // insertion

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an overtime schedule.",
            "meta" => [
                'overtime_schedule' => $overtime_schedule
            ],
            "parameters" => $data,
        ]);

    }

    public function defineAgentOvertime($data = [])
    {
        $available_ot = $this->overtime_schedule
                        ->where('end_event', '>', Carbon::now())
                        ->first();

        if (!$available_ot) {
            return $this->setResponse([
                'code' => 500,
                'title' => "No overtime available.",
                'parameters' => $data,
            ]);
        }

        $auth_id = Auth::id();

        $data = array(
            'title_id' => 2,
            'user_id' => $auth_id,
            'overtime_id' => $available_ot->id
        );

        // data validation

        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {

                if (isset($data['email'])) {
                    $user = $this->user->where('email', $data['email'])->first();
                    if (isset($user->id)) {
                        $data['user_id'] = $user->id;
                    }
                }

                if (!isset($data['user_id'])) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "User ID is not set. | Email is not registered",
                        'parameters' => $data,
                    ]);
                }
            }

            if (!isset($data['title_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Title ID is not set.",
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['user_id'])) {
            if (!$this->user_info->find($data['user_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not available.",
                ]);
            }
        }

        if (isset($data['title_id'])) {
            if (!$this->event_title->find($data['title_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Title ID is not available.",
                ]);
            }
        }

        // check for duplicate schedules
        $does_exist = $this->agent_schedule
            ->where(function($query) use ($data) {
                $query->where([['user_id', $data['user_id']], ['title_id', $data['title_id']], ['overtime_id', $data['overtime_id']]]);
                // $query->where('end_event', '>=', Carbon::now());
            })
            ->first();

        // existence check

        // insertion
        if ($does_exist) {
            $agent_schedule = $does_exist;
        } else {
            $agent_schedule = $this->agent_schedule->init($this->agent_schedule->pullFillable($data));
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

        // Start Create Attendance
        return $this->defineAgentOvertimeAttendance($agent_schedule);
        // End Create Attendance

    }

    public function defineAgentOvertimeAttendance($data = [])
    {
        $attendance = $this->attendance
            ->where('schedule_id', $data['id'])
            ->whereNull('time_out')
            ->first();

        if($attendance) {
            if (!$attendance->save(['time_out' => Carbon::now()])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $attendance->errors(),
                    ],
                ]);
            }
        } else {
            $attendance = $this->attendance->init($this->attendance->pullFillable([
                'schedule_id' => $data['id'],
                'time_in' => Carbon::now()
            ]));

            if(!$attendance->save([
                'schedule_id' => $data['id'],
                'time_in' => Carbon::now()
            ])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $attendance->errors(),
                    ],
                ]);
            }
        }

        $logged_in_user = $this->user->find($data['user_id']);

        $logged_data = [
            "user_id" => $logged_in_user->id,
            "action" => "POST",
            "affected_data" => "Successfully created an attendance for " . $logged_in_user->full_name . "[" . $logged_in_user->access->name . "]."
        ];

        $this->logs->logsInputCheck($logged_data);

        $response = $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an overtime.",
            'meta' => [
                'attendance' => $attendance
            ],
            "parameters" => $data,
        ]);

        return $response;
    }

    public function approveOvertime($data = [], $option)
    {
        $meta_index = 'overtime';

        if (!isset($data['schedules'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Overtime Schedule ID is not set.",
            ]);
        }

        $tempScheds = $data['schedules'];

        foreach ($tempScheds as $key => $schedule) {
            if ($sched = $this->agent_schedule->find($schedule)) {
                if ($sched->overtime_id) {
                    unset($tempScheds[$key]);
                }
            }
        }

        if (!empty($tempScheds)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Schedule ID " . implode(',', $tempScheds) . " not found or not an overtime schedule.",
                "meta" => [
                    $meta_index => $tempScheds,
                ],
                "parameters" => $data,
            ]);
        }

        $overtime_sched = [];

        foreach ($data['schedules'] as $key => $schedule) {
            $overtime = $this->agent_schedule->find($schedule);

            if($option === 'revert') {
                $overtime->approved_by = null;
            } else $overtime->approved_by = Auth::id();
    
            if (!$overtime->save()) {
                return $this->setResponse([
                    "code" => 500,
                    "message" => "Updating overtime schedule was not successful.",
                    "meta" => [
                        "errors" => $overtime->errors(),
                    ],
                    "parameters" => [
                        'schedule_id' => $data['id'],
                    ],
                ]);
            }

            $overtime_schedule[] = $overtime;
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Overtime schedule/s ".($option === 'revert' ? 'disapproved' : 'approved'),
            "description" => "An overtime schedule/s is/are ".($option === 'revert' ? 'disapproved' : 'approved'),
            "meta" => [
                $meta_index => $overtime_schedule
            ],
            "parameters" => $data
        ]);

    }

    public function deleteOvertime($data = [])
    {
        $record = $this->overtime_schedule->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Overtime schedule not found",
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code" => 500,
                "message" => "Deleting overtime schedule was not successful.",
                "meta" => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'schedule_id' => $data['id'],
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Overtime schedule deleted",
            "description" => "An overtime schedule was deleted.",
            "parameters" => [
                "schedule_id" => $data['id'],
            ],
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
            "title" => "Successfully searched agents schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function fetchOvertime($data = [])
    {
        $meta_index = "overtimes";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "overtime";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];
            $data['relations']=['schedules','schedules.user_info'];
            $parameters['schedule_id'] = $data['id'];
        }

        $count_data = $data;

        $overtime = $this->overtime_schedule;

        // $data['relations'] = ["user_info.user", 'title', 'attendances'];

        $result = $this->fetchGeneric($data, $overtime);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No overtime is found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($overtime->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agents overtime",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchOvertimeSchedule($data)
    {
        $result = $this->overtime_schedule;

        $meta_index = "overtime";
        $parameters = [
            "query" => $data['query'],
        ];
        // $data['relations'] = ['user_info.user', 'title'];

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "start_event")) {
                    $data['target'][] = 'start_event';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "end_event")) {
                    $data['target'][] = 'end_event';
                    unset($data['target'][$index]);
                }
            }
        }
        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No overtime schedules are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->overtime_schedule->getModel()));

        if (!is_array($result)) {
            $result = [
                $result,
            ];
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched overtime schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchAgentOvertimeSchedule($data)
    {
        $result = $this->agent_schedule->whereNotNull('overtime_id');

        $meta_index = "agent_schedules";
        $parameters = [
            "query" => $data['query'],
        ];
        $data['relations'] = ['user_info.user', 'title'];

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
                'title' => "No agent's overtime schedules are found",
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
            "title" => "Successfully searched agent's overtime schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function fetchAgentOvertime($data = [])
    {
        $meta_index = "overtime";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "overtimes";
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

        $data['where_not_null'] = [
            'overtime_id'
        ];

        $count_data = $data;

        $overtime = $this->agent_schedule;

        $data['relations'] = ["user_info.user", 'title', 'attendances'];

        $result = $this->fetchGeneric($data, $overtime);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agent overtime is found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($overtime->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agents overtime",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }
}
