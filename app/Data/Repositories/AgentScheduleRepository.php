<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AgentSchedule;
use App\Data\Models\EventTitle;
use App\Data\Models\Leave;
use App\Data\Models\OvertimeSchedule;
use App\Data\Models\UserInfo;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\ClusterRepository;
use App\Data\Repositories\ExcelRepository;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use App\Services\ExcelDateService;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class AgentScheduleRepository extends BaseRepository
{

    protected $agent_schedule,
    $user,
    $user_info,
    $event_title,
    $excel_date,
    $leave,
    $logs,
    $access_level_repo,
    $clusters,
    $notification_repo,
        $overtime_schedule;

    public function __construct(
        AgentSchedule $agentSchedule,
        User $user,
        UserInfo $userInfo,
        EventTitle $eventTitle,
        ExcelDateService $excelDate,
        ClusterRepository $cluster,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository,
        OvertimeSchedule $overtimeSchedule,
        Leave $leave
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->user_info = $userInfo;
        $this->event_title = $eventTitle;
        $this->excel_date = $excelDate;
        $this->logs = $logs_repo;
        $this->clusters = $cluster;
        $this->notification_repo = $notificationRepository;
        $this->overtime_schedule = $overtimeSchedule;
        $this->leave = $leave;
    }

    public function excelData($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $arr = [];
        $firstPage = $excel[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            if (isset($firstPage[$x + 3])) {
                if ($firstPage[$x + 3][1] != null) {
                    if (strtoupper($firstPage[$x + 3][4]) != 'OFF') {
                        $arr[] = [
                            "om_id" => $firstPage[$x + 3][2],
                            "tl_id" => $firstPage[$x + 3][3],
                            "email" => $firstPage[$x + 3][1],
                            "title_id" => 1,
                            "start_event" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][5]),
                            "end_event" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][6]),
                        ];
                    }
                }
            }
        };
        $parameters = $data;
        $excel_data_count = count($arr);
        // $arr['auth_id'] = $data['auth_id'];
        // $result = $this->bulkScheduleInsertion($arr);
        // return $result;

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully extracted excel data.",
            "meta" => [
                "excel_data" => $arr,
                "count" => $excel_data_count,
            ],
            "parameters" => $parameters,
        ]);

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
            $result = $this->defineAgentSchedule($save);
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

    public function defineAgentSchedule($data = [])
    {
        // data validation

        $auth_id = $data['auth_id'];
        unset($data['auth_id']);
        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {

                if (isset($data['email'])) {
                    $user = $this->user->where('email', $data['email'])->first();
                    $om = $this->user->where('email', $data['om_id'])->first();
                    $tl = $this->user->where('email', $data['tl_id'])->first();
                    if (isset($user->uid)) {
                        $data['user_id'] = $user->uid;
                        $data['info'] = $this->user_info->find($user->uid);
                    }

                    if (!isset($data['user_id'])) {
                        return $this->setResponse([
                            'code' => 500,
                            'title' => "Unknown agent email.",
                            'parameters' => $data,
                        ]);
                    }

                    if (isset($om->id)) {
                        $data['om_id'] = $om->uid;
                    }

                    if (!isset($data['om_id'])) {
                        return $this->setResponse([
                            'code' => 500,
                            'title' => "Unknown OM email.", 
                            'parameters' => $data,
                        ]);
                    }

                    if (isset($tl->id)) {
                        $data['tl_id'] = $tl->uid;
                    }

                    if (!isset($data['tl_id'])) {
                        return $this->setResponse([
                            'code' => 500,
                            'title' => "Unknown TL email.",
                            'parameters' => $data,
                        ]);
                    }
                }
            }

            if (!isset($data['title_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Title ID is not set.",
                    'parameters' => $data,
                ]);
            }

            if (!isset($data['start_event'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Start date is not set.",
                    'parameters' => $data,
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['user_id'])) {
            if (!$this->user_info->find($data['user_id'])) {
                $data['email'] = "UserID# " . $data['user_id'];
                return $this->setResponse([
                    'code' => 500,
                    'parameters' => $data,
                    'title' => "User ID is not available.",
                ]);
            } else {
                $data['email'] = $this->user->where('uid', $data['user_id'])->first()->email;
            }
        }

        if (isset($data['title_id'])) {
            if (!$this->event_title->find($data['title_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'parameters' => $data,

                    'title' => "Title ID is not available.",
                ]);
            }
        }

        if (isset($data['id'])) {
            $does_exist = $this->agent_schedule->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'parameters' => $data,
                    'title' => 'Agent Schedule ID does not exist.',
                ]);
            }
        }

        // check if start event is before end event
        $start = new DateTime($data['start_event']);
        $end = new DateTime($data['end_event']);
        if ($end->format('Y-m-d H:i:s') < $start->format('Y-m-d H:i:s')) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Invalid dates.",
                'parameters' => $data,
            ]);
        }

        //validate if a schedule is already made within these dates
        $date_hit = $this->agent_schedule
            ->where('user_id', $data['user_id'])
            ->where(function ($query) use ($data) {
                $query
                    ->whereBetween('start_event', [$data['start_event'], $data['end_event']])
                    ->orWhereBetween('end_event', [$data['start_event'], $data['end_event']]);
            })->first();

        if (!empty($date_hit) && !isset($data["id"])) {
            return $this->setResponse([
                'code' => 500,
                'parameters' => $data,
                'meta' => $date_hit,
                'title' => 'A schedule within the dates set is already created.',
            ]);
        }

        if (!empty($date_hit) && isset($data["id"])) {
            if ($data["id"] != $date_hit->id) {
                return $this->setResponse([
                    'code' => 500,
                    'parameters' => $data,
                    'meta' => $date_hit,
                    'title' => 'A schedule within the dates set is already created.',
                ]);
            }
        }

        // check for duplicate schedules
        $does_exist = $this->agent_schedule
            ->where('user_id', $data['user_id'])
            ->where('title_id', $data['title_id'])
            ->where('start_event', $data['start_event'])
            ->where('end_event', $data['end_event'])
            ->first();

        // existence check

        // insertion
        if (isset($data['id'])) {
            $agent_schedule = $this->agent_schedule->find($data['id']);
        } else if ($does_exist) {
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

        if (isset($auth_id) ||
            !is_numeric($auth_id) ||
            $auth_id <= 0) {
            $logged_in_user = $this->user->find($auth_id);
            $current_employee = isset($data['user_id']) ? $this->user->where('uid',$data['user_id'])->first() : $this->user->find($agent_schedule->user_id);
            if (!$logged_in_user) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not available.",
                    'parameters' => $data,

                ]);
            }

            $logged_data = [
                "user_id" => $auth_id,
                "action" => "POST",
                "affected_data" => "Successfully created a schedule for " . $current_employee->full_name . "[" . $current_employee->access->name . "] on " . $data['start_event'] . " to " . $data['end_event'] . " via excel upload by " . $logged_in_user->full_name . " [" . $logged_in_user->access->name . "].",
            ];
            $this->logs->logsInputCheck($logged_data);
        }

        // insertion

        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $auth_id,
            'recipient_id' => isset($data['user_id']) ? $data['user_id'] : $agent_schedule->user_id,
            'type' => 'schedules.assign',
            'type_id' => $agent_schedule->id,
        ]);

        // insertion of cluster
        if (isset($data['cluster']) && isset($data['tl_id'])) {
            $om = $this->user->where('email', $data['cluster'])->first();
            $tl = $this->user->where('email', $data['tl_id'])->first();
            $arr = [
                "om_id" => $om->id,
                "tl_id" => $tl->id,
                "agent_id" => $data['user_id'],
            ];
            $this->clusters->defineCluster($arr);

        }

        //leave checker
        $leave = $this->leave->where('user_id', $agent_schedule->user_id)
            ->where('start_event', '>=', $agent_schedule->start_event)
            ->where('end_event', '<=', $agent_schedule->end_event)
            ->first();

        if ($leave) {
            $agent_schedule->update([
                'leave_id' => $leave->id,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an agent schedule.",
            "meta" => $agent_schedule,
            'parameters' => $data,

        ]);

    }

    public function deleteAgentSchedule($data = [])
    {
        $record = $this->agent_schedule->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Agent schedule not found",
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code" => 500,
                "message" => "Deleting agent schedule was not successful.",
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
            "title" => "Agent schedule deleted",
            "description" => "An agent schedule was deleted.",
            "parameters" => [
                "schedule_id" => $data['id'],
            ],
        ]);

    }

    public function fetchAgentSchedule($data = [])
    {
        $meta_index = "agent_schedules";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "agent_schedule";
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

        //filter by overtime id
        if (isset($data['overtime_id'])) {
            $data['where'][] = [
                "target" => "overtime_id",
                "operator" => "=",
                "value" => $data['overtime_id'],
            ];
        }

        //filter by tl id
        if (isset($data['tl_id'])) {
            $data['where'][] = [
                "target" => "tl_id",
                "operator" => "=",
                "value" => $data['tl_id'],
            ];
        }

        //filter by om id
        if (isset($data['om_id'])) {
            $data['where'][] = [
                "target" => "om_id",
                "operator" => "=",
                "value" => $data['om_id'],
            ];

            //show tl only
            if (isset($data['tl'])) {
                $data['wherehas'][] = [
                    'relation' => 'user_data',
                    'target' => [
                        [
                            'column' => 'access_id',
                            'operator' => '=',
                            'value' => '16',
                        ],
                    ],
                ];
            }
        }

        //filter by approved_by
        if (isset($data['approved'])) {
            if ($data['approved'] == "true") {
                $data['where_not_null'] = ['approved_by'];
            } else {
                $data['where_null'] = ['approved_by'];
            }
        }

        //filter by start date
        if (isset($data['start_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => ">=",
                "value" => $data['start_date'],
            ];
        }

        //filter by end date
        if (isset($data['end_date'])) {
            $data['where'][] = [
                "target" => "end_event",
                "operator" => "<=",
                "value" => $data['end_date'],
            ];
        }

        $count_data = $data;

        $data['relations'] = ['user_info.user', 'tl_info.user', 'om_info.user', 'title', 'leave'];

        $result = $this->fetchGeneric($data, $this->agent_schedule);

        if (!$result) {
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

        if (!is_array($result)) {
            $result = [
                $result,
            ];
        }

        // foreach ($result as $key => $value) {
        //     $value->team_leader = $value->user_info->user->team_leader;
        //     $value->operations_manager = $value->user_info->user->operations_manager;
        //     unset($value->user_info->user);
        // }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agent schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function fetchAllAgentsWithSchedule($data = [])
    {
        $meta_index = "agents";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "agent";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
                [
                    "target" => "access_id",
                    "operator" => "=",
                    "value" => '17',
                ],
            ];

            $parameters['agent_id'] = $data['id'];

        } else {

            $data['where'] = [
                [
                    "target" => "access_id",
                    "operator" => "=",
                    "value" => '17',
                ],
            ];
        }

        $count_data = $data;

        $data['relations'][] = 'info';
        $data['relations'][] = 'schedule.title';
        $data['relations'][] = 'leaves';

        //filter by leave status
        if (isset($data['leave_status'])) {
            $this->user = $this->user->with(['leaves' => function ($query) use ($data) {
                $query->where('status', $data['leave_status']);
            }]);

            $data['wherehas'][] = [
                'relation' => 'leaves',
                'target' => [
                    [
                        'column' => 'status',
                        'value' => $data['leave_status'],
                    ],
                ],
            ];
        }

        if (isset($data['search']) || isset($data['target'])) {
            if (!is_array($data['target'])) {
                $data['target'] = (array) $data['target'];
            }

            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'info.firstname';
                    $data['target'][] = 'info.middlename';
                    $data['target'][] = 'info.lastname';
                    unset($data['target'][$index]);
                }
            }

            $result = $this->genericSearch($data, $this->user)->get()->all();
        } else {
            $result = $this->fetchGeneric($data, $this->user);
        }

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agents with schedules are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agent schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
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
        $data['relations'] = ['user_info.user', 'tl_info.user', 'om_info.user', 'title', 'leave'];

        //filter by overtime id
        if (isset($data['overtime_id'])) {
            $data['where'][] = [
                "target" => "overtime_id",
                "operator" => "=",
                "value" => $data['overtime_id'],
            ];
        }

        //filter by tl id
        if (isset($data['tl_id'])) {
            $data['where'][] = [
                "target" => "tl_id",
                "operator" => "=",
                "value" => $data['tl_id'],
            ];
        }

        //filter by om id
        if (isset($data['om_id'])) {
            $data['where'][] = [
                "target" => "om_id",
                "operator" => "=",
                "value" => $data['om_id'],
            ];

            //show tl only
            if (isset($data['tl'])) {
                $data['wherehas'][] = [
                    'relation' => 'user_data',
                    'target' => [
                        [
                            'column' => 'access_id',
                            'operator' => '=',
                            'value' => '16',
                        ],
                    ],
                ];
            }
        }

        //filter by approved_by
        if (isset($data['approved'])) {
            if ($data['approved'] == "true") {
                $data['where_not_null'] = ['approved_by'];
            } else {
                $data['where_null'] = ['approved_by'];
            }
        }

        //filter by start date
        if (isset($data['start_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => ">=",
                "value" => $data['start_date'],
            ];
        }

        //filter by end date
        if (isset($data['end_date'])) {
            $data['where'][] = [
                "target" => "end_event",
                "operator" => "<=",
                "value" => $data['end_date'],
            ];
        }

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'user_info.firstname';
                    $data['target'][] = 'user_info.middlename';
                    $data['target'][] = 'user_info.lastname';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "ot_id")) {
                    $data['target'][] = 'overtime_schedule.id';
                    unset($data['target'][$index]);
                }
            }
        }

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
        $count_data["search"] = true;
        $count = $this->countData($count_data, refresh_model($this->agent_schedule->getModel()));

        if (!is_array($result)) {
            $result = [
                $result,
            ];
        }

        // foreach ($result as $key => $value) {
        //     $value->team_leader = $value->user_info->user->team_leader;
        //     $value->operations_manager = $value->user_info->user->operations_manager;
        //     unset($value->user_info->user);
        // }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched agent schedules",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function agentScheduleStats($data)
    {
        $result = $this->agent_schedule;

        $meta_index = "agent_schedules";

        $sparkline = array();

        $title = null;

        if (isset($data['filter'])) {
            $parameters = [
                'filter' => $data['filter'],
            ];
        } else {
            $parameters = [];
        }

        $data['columns'] = ['agent_schedules.*'];
        $data['where'] = array();
        $data['where_between'] = array();
        $data['no_all_method'] = true;
        $data['relations'] = ['user_info'];

        if (isset($data['filter']) && $data['filter'] === 'working') {

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Working.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now(),
            ],
                [
                    'target' => 'end_event',
                    'operator' => '>=',
                    'value' => Carbon::now(),
                ],
                [
                    'target' => 'title_id',
                    'operator' => '=',
                    'value' => 1,
                ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_working', 1);
            }

        } else if (isset($data['filter']) && $data['filter'] === 'absent') {

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Absent.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'agent_schedules.start_event',
                'operator' => '<=',
                'value' => Carbon::now(),
            ],
                [
                    'target' => 'agent_schedules.end_event',
                    'operator' => '>=',
                    'value' => Carbon::now(),
                ],
                [
                    'target' => 'title_id',
                    'operator' => '=',
                    'value' => 1,
                ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_present', 0);
            }

        } else if (isset($data['filter']) && $data['filter'] === 'off-duty') {

            $result = $this->user;

            $data['columns'] = ['id', 'uid', 'access_id'];

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Off-Duty.";

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_agent', 1)->where('has_schedule', 0);
            }

        } else if (isset($data['filter']) && $data['filter'] === 'on-break') {

            $title = "Agent on break.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now(),
            ],
                [
                    'target' => 'end_event',
                    'operator' => '>=',
                    'value' => Carbon::now(),
                ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_present', 1)->where('is_working', 0);
            }

        } else if (isset($data['filter']) && $data['filter'] === 'on-leave') {

            $title = "Agent on leave.";

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now(),
            ],
                [
                    'target' => 'end_event',
                    'operator' => '>=',
                    'value' => Carbon::now(),
                ],
                [
                    'target' => 'title_id',
                    'operator' => '!=',
                    'value' => 1,
                ],
            ));

            $result = $this->fetchGeneric($data, $result);

        } else {

            $sparkline = $this->sparkline($data, $result);

            $title = "Agent Scheduled.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now(),
            ],
                [
                    'target' => 'end_event',
                    'operator' => '>=',
                    'value' => Carbon::now(),
                ],
                [
                    'target' => 'title_id',
                    'operator' => '=',
                    'value' => 1,
                ]));

            $result = $this->fetchGeneric($data, $result);

        }

        if ($result == null) {
            return $this->setResponse([
                "code" => 404,
                "title" => "No agent schedules are found",
                "meta" => [
                    $meta_index => $result,
                    "sparkline" => $sparkline,
                    "count" => $result->count(),
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => $title,
            "meta" => [
                $meta_index => $result,
                "sparkline" => $sparkline,
                "count" => $result->count(),
            ],
            "parameters" => $parameters,
        ]);
    }

    private function sparkline($data, $result, $filter = null)
    {

        $sparkline = array();

        $now = Carbon::now();

        $previous = Carbon::now()->subDays(9)->format('Y-m-d');

        $period = CarbonPeriod::create($previous, $now->format('Y-m-d'))->toArray();

        $now = $now->addDays(1)->format('Y-m-d');

        if ($filter === 'working') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now],
            ]));

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1,
            ]));

            $data['relations'] = array('attendances');

            $count_attr = 'attendances';

            $only_attr = ['date', 'attendances'];
        } else if ($filter === 'on-leave') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now],
            ]));

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '!=',
                'value' => 1,
            ]));

            $only_attr = ['start_event', 'end_event'];
        } else if ($filter === 'off-duty') {
            $data['relations'] = array('schedule' => function ($query) use ($previous, $now) {
                $query->whereBetween('start_event', [$previous, $now]);
            });

            $only_attr = ['schedule'];

            $result = $this->fetchGeneric($data, $result)->where('is_agent', 1);
        } else if ($filter === 'absent') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now],
            ]));

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1,
            ]));

            $count_attr = 'attendances';

            $only_attr = ['date', 'attendances', 'start_event', 'end_event'];
        } else {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now],
            ]));

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1,
            ]));

            $only_attr = ['start_event', 'end_event'];
        }

        if ($filter !== 'off-duty') {
            $result = $this->fetchGeneric($data, $result);
        }

        if ($result) {
            $result = $result->map(function ($result) use ($only_attr) {
                return collect($result->toArray())
                    ->only($only_attr)
                    ->all();
            });

            foreach ($period as $key => $date) {
                $count = 0;

                foreach ($result as $resKey => $value) {

                    $dates = array();

                    if ($filter === 'working') {

                        if (!empty($value[$count_attr])) {

                            $temp = array();

                            $time_in = Carbon::parse($value[$count_attr][0]['time_in'])->format('Y-m-d');

                            $time_out = ($value[$count_attr][count($value[$count_attr]) - 1]['time_out']) ? Carbon::parse($value[$count_attr][count($value[$count_attr]) - 1]['time_out'])->format('Y-m-d') : Carbon::now()->format('Y-m-d');

                            $dates = CarbonPeriod::create($time_in, $time_out);

                        }

                        foreach ($dates as $filtered_date) {
                            if (Carbon::parse($filtered_date->format('Y-m-d'))->equalTo($date)) {
                                $count += 1;
                            }
                        }

                    } else if ($filter === 'on-leave') {

                        $emp_sched = array();

                        $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                        $end = Carbon::parse($value['end_event'])->format('Y-m-d');

                        $periods = CarbonPeriod::create($start, $end);

                        foreach ($periods as $period) {
                            $emp_sched[] = $period->format('Y-m-d');
                        }

                        if (in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
                            $count += 1;
                        }

                    } else if ($filter === 'absent') {

                        $temp = array();

                        $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                        $end = ($value['end_event']) ? Carbon::parse($value['end_event'])->format('Y-m-d') : Carbon::now()->format('Y-m-d');

                        $dates = CarbonPeriod::create($start, $end);

                        $value[$count_attr] = (count($value[$count_attr]) > 0) ? 0 : 1;

                        foreach ($dates as $filtered_date) {
                            if (Carbon::parse($filtered_date->format('Y-m-d'))->equalTo($date)) {
                                $count += $value[$count_attr];
                            }
                        }

                    } else if ($filter === 'off-duty') {

                        $emp_sched = array();

                        foreach ($value['schedule'] as $key => $value) {
                            $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                            $end = Carbon::parse($value['end_event'])->format('Y-m-d');

                            $periods = CarbonPeriod::create($start, $end);

                            foreach ($periods as $period) {
                                $emp_sched[] = $period->format('Y-m-d');
                            }
                        }

                        if (!in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
                            $count += 1;
                        }

                    } else {

                        $emp_sched = array();

                        $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                        $end = Carbon::parse($value['end_event'])->format('Y-m-d');

                        $periods = CarbonPeriod::create($start, $end);

                        foreach ($periods as $period) {
                            $emp_sched[] = $period->format('Y-m-d');
                        }

                        if (in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
                            $count += 1;
                        }

                    }
                }

                array_push($sparkline, $count);
            }

            $result = $sparkline;
        }

        if (!$result) {
            $result = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        }

        return $result;
    }

    public function workInfo($data, $option)
    {

        $result = $this->user;

        $meta_index = "agent_schedules";

        $sparkline = array();

        $parameters = array();

        if ($option === 'today') {

            $title = "Today's Activity";

            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();

            $data['relations'] = array('schedule' => function ($query) use ($start, $end) {
                $query->where([['start_event', '>=', $start], ['end_event', '<=', $end]]);
                $query->orWhereHas('overtime_schedule', function ($ot_query) use ($start, $end) {
                    $ot_query->where('start_event', '>=', $start);
                    $ot_query->where('end_event', '<=', $end);
                });
            },
            );

            $data['wherehas_by_relations'] = array(
                'target' => 'schedule',
                'query' => function ($query) use ($start, $end) {
                    $query->where([['start_event', '>=', $start], ['end_event', '<=', $end]]);
                    $query->orWhereHas('overtime_schedule', function ($ot_query) use ($start, $end) {
                        $ot_query->where('start_event', '>=', $start);
                        $ot_query->where('end_event', '<', $end);
                    });
                },
            );

        } else if ($option === 'report') {

            if (isset($data['start']) && isset($data['end'])) {

                $title = "Work Reports (" . $data['start'] . " to " . $data['end'] . ")";

                $parameters = [
                    'start' => $data['start'],
                    'end' => $data['end'],
                ];

                if (isset($data['userid'])) {
                    $parameters = [
                        'userid' => $data['userid'],
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ];

                    $data['where'] = array([
                        'target' => 'uid',
                        'operator' => '=',
                        'value' => $data['userid'],
                    ]);
                } else {
                    $data['where'] = [
                        [
                            "target" => "access_id",
                            "operator" => "=",
                            "value" => '17',
                        ],
                    ];

                }

                // if (isset($data['tl_id']) && isset($data['om_id'])) {
                //     $result = $result->where(function ($q) use ($data) {
                //         if (isset($data['operator']) && $data['operator'] === 'or') {
                //             $q->whereHas('hierarchy', function ($q) use ($data) {
                //                 $q->where('parent_id', $data['tl_id']);
                //             });
                //             $q->orWhereHas('hierarchy', function ($q) use ($data) {
                //                 $q->whereHas('parentInfo', function ($q) use ($data) {
                //                     $q->whereHas('accesslevelhierarchy', function ($q) use ($data) {
                //                         $q->where('parent_id', $data['om_id']);
                //                     });
                //                 });
                //             });
                //         } else {
                //             $q->whereHas('hierarchy', function ($q) use ($data) {
                //                 $q->where('parent_id', $data['tl_id']);
                //                 $q->whereHas('parentInfo', function ($q) use ($data) {
                //                     $q->whereHas('accesslevelhierarchy', function ($q) use ($data) {
                //                         $q->where('parent_id', $data['om_id']);
                //                     });
                //                 });
                //             });
                //         }
                //     });
                // } else if (isset($data['tl_id'])) {
                //     $result = $result->where(function ($q) use ($data) {
                //         $q->whereHas('hierarchy', function ($q) use ($data) {
                //             $q->where('parent_id', $data['tl_id']);
                //         });
                //     });
                // } else if (isset($data['om_id'])) {
                //     $result = $result->where(function ($q) use ($data) {
                //         $q->whereHas('hierarchy', function ($q) use ($data) {
                //             $q->whereHas('parentInfo', function ($q) use ($data) {
                //                 $q->whereHas('accesslevelhierarchy', function ($q) use ($data) {
                //                     $q->where('parent_id', $data['om_id']);
                //                 });
                //             });
                //         });
                //     });
                // }

                if (isset($data['tl_id'])) {
                    $result = $result->where(function ($q) use ($data) {
                        $q->whereHas('schedule', function ($q) use ($data) {
                            $q->where('tl_id', $data['tl_id']);
                            $q->where('start_event', '>=', $data['start']);
                            $q->where('end_event', '<=', $data['end']);
                        });
                    });
                }

                if (isset($data['om_id'])) {
                    $result = $result->where(function ($q) use ($data) {
                        $q->whereHas('schedule', function ($q) use ($data) {
                            $q->where('om_id', $data['om_id']);
                            $q->where('start_event', '>=', $data['start']);
                            $q->where('end_event', '<=', $data['end']);
                        });
                    });
                }

                $data['relations'] = array('schedule' => function ($query) use ($parameters, $data) {
                    $end = Carbon::parse($parameters['end']);
                    $end = ($end->isToday()) ? Carbon::now() : $end->addDays(1);

                    $query->where(function ($query) use ($parameters, $end) {
                        $query->where([['start_event', '>=', Carbon::parse($parameters['start'])], ['start_event', '<', $end]]);
                        $query->orWhereHas('overtime_schedule', function ($ot_query) use ($parameters, $end) {
                            $ot_query->where('start_event', '>=', Carbon::parse($parameters['start']));
                            $ot_query->where('end_event', '<', $end);
                        });
                    });

                    if (isset($data['om_id'])) {
                        $query->where('om_id', $data['om_id']);
                    }

                    if (isset($data['tl_id'])) {
                        $query->where('tl_id', $data['tl_id']);
                    }

                    if (isset($data['remarks'])) {
                        $query->where('remarks', $data['remarks']);
                    }
                });

                if (!isset($parameters['userid'])) {
                    $data['wherehas_by_relations'] = array(
                        'target' => 'schedule',
                        'query' => function ($query) use ($parameters) {
                            $end = Carbon::parse($parameters['end']);
                            $end = ($end->isToday()) ? Carbon::now() : $end->addDays(1);

                            $query->where(function ($query) use ($parameters, $end) {
                                $query->where([['start_event', '>=', Carbon::parse($parameters['start'])], ['start_event', '<', $end]]);
                                $query->orWhereHas('overtime_schedule', function ($ot_query) use ($parameters) {
                                    $end = Carbon::parse($parameters['end']);
                                    $end = $end->addDays(1);

                                    $ot_query->where('start_event', '>=', Carbon::parse($parameters['start']));
                                    $ot_query->where('end_event', '<', $end);
                                });
                            });
                        });
                }

            } else {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Required parameters are not set.",
                    "meta" => [
                        $meta_index => null,
                        "count" => null,
                    ],
                    "parameters" => $parameters,
                ]);
            }

        }
        // $data['relations'][] = 'leaves';
        $data['columns'] = ['users.*'];
        $data['no_all_method'] = true;

        $data['wherehas'] = array([
            'relation' => 'access',
            'target' => array([
                'column' => 'code',
                'value' => 'representative_op',
            ]),
        ]);

        if (isset($data['target'])) {
            if (!is_array($data['target'])) {
                $data['target'] = (array) $data['target'];
            }

            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'info.firstname';
                    $data['target'][] = 'info.middlename';
                    $data['target'][] = 'info.lastname';
                    unset($data['target'][$index]);
                }
            }

            $result = $this->genericSearch($data, $result)->get();
        } else {
            $result = $this->fetchGeneric($data, $result);
        }

        if ($result == null) {
            return $this->setResponse([
                "code" => 404,
                "title" => "No activities found at the moment",
                "meta" => [
                    $meta_index => $result,
                    "count" => $result->count(),
                ],
                "parameters" => $parameters,
            ]);
        }

        //filter schedule according to log status
        foreach ($result as $key => $res) {
            if (isset($data['log_status'])) {
                $schedule = $res->schedule->filter(function ($query) use ($data) {
                    $search_array = array_map('strtolower', $query->log_status);

                    if (is_array($data['log_status'])) {
                        return !empty(array_intersect($data['log_status'], $search_array));
                    } else {
                        return in_array(strtolower($data['log_status']), $search_array);
                    }

                });
                $result[$key]->schedule = $schedule;
            }
        }

        $summary = [
            'ncns' => 0,
            'leave' => 0,
            'present' => 0,
            'absent' => 0,
        ];

        foreach ($result as $key => $value) {
            $summary['ncns'] += $value->summary['ncns'];
            $summary['leave'] += $value->summary['leave'];
            $summary['present'] += $value->summary['present'];
            $summary['absent'] += $value->summary['absent'];
        }

        $result_data = $result->toArray();

        //sanitize result schedule data
        if (isset($data['log_status'])) {
            foreach ($result_data as $key => $value) {
                unset($result_data[$key]['schedule']);
                $result_data[$key]['schedule'] = array_merge($result[$key]->schedule->toArray());
            }
        }

        return $this->setResponse([
            "code" => 200,
            "title" => $title,
            "meta" => [
                $meta_index => $result_data,
                "count" => $result->count(),
                "summary" => $summary,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function conformance($data = [], $id)
    {
        $meta_index = "agent_schedules";

        $schedule = $this->agent_schedule->find($id);

        if (!isset($data['conformance'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter 'conformance' is required.",
                "meta" => [
                    $meta_index => $schedule,
                ],
                "parameters" => $data,
            ]);
        } else {
            if (!is_numeric($data['conformance'])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Parameter 'conformance' should be a digit.",
                    "meta" => [
                        $meta_index => $schedule,
                    ],
                    "parameters" => $data,
                ]);
            } else {
                if (is_numeric($data['conformance'])) {
                    if ($data['conformance'] > 100) {
                        return $this->setResponse([
                            "code" => 500,
                            "title" => "Conformance value must not be greater than 100.",
                            "meta" => [
                                $meta_index => $schedule,
                            ],
                            "parameters" => $data,
                        ]);
                    }
                }
            }
        }

        if ($schedule) {
            if (!$schedule->save([
                'conformance' => $data['conformance'],
            ])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $schedule->errors(),
                    ],
                ]);
            }

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully defined a conformance.",
                "meta" => [
                    $meta_index => $this->agent_schedule->find($schedule->id),
                ],
                "parameters" => $data,
            ]);
        }

        return $this->setResponse([
            "code" => 500,
            "title" => "Schedule ID does not exists.",
            "meta" => [
                $meta_index => $schedule,
            ],
            "parameters" => $data,
        ]);
    }

    public function conformanceBulkUpdate($data = [])
    {
        $meta_index = "agent_schedules";

        if (!isset($data['schedules'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter schedules is required.",
                "parameters" => $data,
            ]);
        }

        if (!is_array($data['schedules'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter schedules must be an array of schedule ID.",
                "parameters" => $data,
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

        if (!isset($data['conformance'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter 'conformance' is required.",
                "parameters" => $data,
            ]);
        } else {
            if (!is_numeric($data['conformance'])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Parameter 'conformance' should be a digit.",
                    "parameters" => $data,
                ]);
            } else {
                if (is_numeric($data['conformance'])) {
                    if ($data['conformance'] > 100) {
                        return $this->setResponse([
                            "code" => 500,
                            "title" => "Conformance value must not be greater than 100.",
                            "parameters" => $data,
                        ]);
                    }
                }
            }
        }

        foreach ($data['schedules'] as $key => $schedule) {
            $schedule = $this->agent_schedule->find($schedule);

            if (!$schedule->save([
                'conformance' => $data['conformance'],
            ])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $schedule->errors(),
                    ],
                ]);
            }
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully updated a conformance.",
            "meta" => [
                $meta_index => $this->agent_schedule->whereIn('id', $data['schedules'])->get(),
            ],
            "parameters" => $data,
        ]);
    }

    public function remarks($data = [], $id)
    {
        $meta_index = "agent_schedules";

        $schedule = $this->agent_schedule->find($id);

        if (!isset($data['remarks'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter 'remarks' is required.",
                "meta" => [
                    $meta_index => $schedule,
                ],
                "parameters" => $data,
            ]);
        } else {
            if (!is_numeric($data['remarks']) || ($data['remarks'] >= 0 && $data['remarks'] > 1)) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Parameter 'remarks' should be 0 or 1.",
                    "meta" => [
                        $meta_index => $schedule,
                    ],
                    "parameters" => $data,
                ]);
            }
        }

        if ($schedule) {
            if (!$schedule->save([
                'remarks' => $data['remarks'],
            ])) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $schedule->errors(),
                    ],
                ]);
            }

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully defined a remarks.",
                "meta" => [
                    $meta_index => $schedule,
                ],
                "parameters" => $data,
            ]);
        }

        return $this->setResponse([
            "code" => 500,
            "title" => "Schedule ID does not exists.",
            "meta" => [
                $meta_index => $schedule,
            ],
            "parameters" => $data,
        ]);
    }

    public function noTimeOut($data = [])
    {
        $status = null;
        $previous = null;
        $ongoing = null;
        $upcoming = null;
        $leave = null;
        $schedule = null;
        $meta_index = "schedule";
        $user = $this->user;

        if (!isset($data['userid'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Parameter 'userid' is required.",
                "parameters" => $data,
            ]);
        }

        $user = $user->find($data['userid']);

        if (!$user) {
            return $this->setResponse([
                "code" => 500,
                "title" => "User ID does not exists.",
                "meta" => [
                    'agent' => $user,
                ],
                "parameters" => $data,
            ]);
        }

        // check if today is on leave

        $now = Carbon::now();

        $leave = $this->leave
            ->where("user_id", $data["userid"])
            ->where("start_event", "<=", $now)
            ->where("end_event", ">=", $now)
            ->where("status", "approved")
            ->first();

        // get previous schedules with no timeout
        $previous = $user->schedule()
            ->whereHas('attendances', function ($q) {
                $q->whereNull('time_out');
            })
            ->get();

        $previous = collect($previous)->where('start_event', '<', Carbon::now())->sortBy('start_event')->first();

        if ($previous) {
            $end = Carbon::parse($previous->end_event)->addMinutes(15);
            if (Carbon::now()->isAfter($end)) {
                $previous = null;
            }
        }
        // get overtime schedule
        $overtime = $this->overtime_schedule
            ->where("start_event", "<=", $now)
            ->where("end_event", ">=", $now)
            ->first();

        // check if user has ongoing overtime
        if ($overtime) {
            $overtime = $this->agent_schedule
                ->where("user_id", $data["userid"])
                ->where("overtime_id", $overtime->id)
                ->first();
        }

        if ($overtime) {
            $ongoing = $overtime;
        } else {
            // get ongoing schedules
            $ongoing = $this->agent_schedule
                ->where("user_id", $data["userid"])
                ->where("start_event", "<=", $now)
                ->where("end_event", ">=", $now)
                ->first();
        }

        // get upcoming schedules
        $upcoming = $user->schedule()
        // ->whereDoesntHave('attendances')
            ->get();

        $upcoming = collect($upcoming)->where('start_event', '>', Carbon::now())->sortBy('start_event')->first();

        if ($previous) {
            $status = "previous";
            $schedule = $previous;
        } else {
            if ($ongoing) {
                $status = "ongoing";
                $schedule = $ongoing;
            } else {
                $status = "upcoming";
                $schedule = $upcoming;
            }
        }

        if ($leave) {
            $status = "on-leave";
        }

        //  fetch overtime schedule
        $now = Carbon::now()->addMinutes(15)->toDateTimeString();
        $ot_schedule = $this->overtime_schedule
            ->where("start_event", "<=", $now)
            ->where("end_event", ">=", $now)
            ->first();

        if ($ot_schedule && $schedule) {
            if (Carbon::parse($ot_schedule->start_event)->isBetween(Carbon::parse($schedule->start_event)->subHours(2), Carbon::parse($schedule->end_event)->addHour(), true)) {
                $ot_schedule = null;
            }
            if ($ot_schedule) {
                if (Carbon::parse($ot_schedule->end_event)->isBetween(Carbon::parse($schedule->start_event)->subHours(2), Carbon::parse($schedule->end_event)->addHour(), true)) {
                    $ot_schedule = null;
                }
            }
            // if fetched schedule is an ot schedule
            if ($ot_schedule) {
                if ($ot_schedule->id == $schedule->overtime_id) {
                    $ot_schedule = null;
                }
            }
        }

        return $this->setResponse([
            "code" => 200,
            "title" => $status . " schedule.",
            "meta" => [
                'agent' => $user,
                $meta_index => $schedule,
                "leave" => $leave,
                "overtime" => $ot_schedule,
            ],
            "parameters" => $data,
        ]);

    }

    public function missedLogs($data = [])
    {
        // filter params id, tl_id, om_id
        $result = $this->agent_schedule->with("coaching", "coaching.filed_by", "coaching.verified_by")->where("title_id", 1)->orderBy("start_event", "desc")->get();

        if (isset($data["id"]) && $data["id"]) {
            $result = collect($result)->where('user_info.id', $data["id"]);
        }

        if (isset($data["tl_id"]) && $data["tl_id"]) {
            $result = collect($result)->where('tl_id', $data["tl_id"]);
        }

        if (isset($data["om_id"]) && $data["om_id"]) {
            $result = collect($result)->where('om_id', $data["om_id"]);
        }

        if (isset($data['status'])) {
            $result = collect($result)->filter(function ($i) use ($data) {
                if ($i['coaching']) {
                    if (strtolower($i['coaching']['status']) == strtolower($data["status"])) {
                        return $i;
                    }
                }
            });
        }

        if (isset($data["query"]) && $data["query"]) {
            $result = collect($result)->filter(function ($i) use ($data) {
                if (strpos(strtolower($i['user_info']['full_name']), strtolower($data['query'])) !== false) {
                    return $i;
                }
            });
        }

        $result = array_values(collect($result)->filter(function ($i) {
            if (count(array_intersect($i->log_status, ["tardy", "undertime", "no_timeout"])) > 0) {
                return $i;
            }
        })->toArray());
        return $this->setResponse([
            "code" => 200,
            "title" => "successfully fetch missed logs",
            "meta" => [
                'missed_logs' => isset($data["page"]) ? $this->paginate($result, $data["perpage"], $data["page"]) : $result,
            ],
        ]);
    }
}