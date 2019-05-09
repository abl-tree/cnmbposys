<?php
/**
 * Created by PhpStorm.
 * User: Janrey
 * Date: 30/10/2018
 * Time: 2:12 PM
 */

namespace App\Data\Repositories;
ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\AgentSchedule;
use App\Data\Models\UserInfo;
use App\Data\Models\EventTitle;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Repositories\ExcelRepository;
use App\Data\Repositories\BaseRepository;
use App\Services\ExcelDateService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use App\Data\Repositories\AccessLevelHierarchyRepository;

class AgentScheduleRepository extends BaseRepository
{

    protected 
        $agent_schedule,
        $user,
        $user_info,
        $event_title,
        $excel_date,
        $logs,
        $access_level_repo,
        $access_level,
        $notification_repo;

    public function __construct(
        AgentSchedule $agentSchedule,
        User $user,
        UserInfo $userInfo,
        EventTitle $eventTitle,
        ExcelDateService $excelDate,
        AccessLevelHierarchyRepository $access_level_repository,
        AccessLevelHierarchy $access_level_model,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository
    ) {
        $this->agent_schedule = $agentSchedule;
        $this->user = $user;
        $this->user_info = $userInfo;
        $this->event_title = $eventTitle;
        $this->excel_date = $excelDate;
        $this->logs = $logs_repo;
        $this->access_level_repo = $access_level_repository;
        $this->access_level = $access_level_model;
        $this->notification_repo = $notificationRepository;
    }

    public function excelData($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $arr = [];
        $firstPage  = $excel[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            if(isset($firstPage[$x+3])){
                if($firstPage[$x+3][1] != null)
                {
                    if (strtoupper($firstPage[$x + 3][4]) != 'OFF') {
                        $arr[] = array(
                            "cluster" => $firstPage[$x + 3][2],
                            "email" => $firstPage[$x + 3][1],
                            "title_id" => 1,
                            "start_event" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][4]),
                            "end_event" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][5]),
                        );
                    }
                }
            }
        }
        $arr['auth_id'] = $data['auth_id'];
        $result = $this->bulkScheduleInsertion($arr);
        return $result;

    }

    public function bulkScheduleInsertion($data = [])
    {
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
            $result = $this->defineAgentSchedule($save);
            // logs POST data

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

    public function defineAgentSchedule($data = [])
    {
        // data validation

        $auth_id = $data['auth_id'];
        unset($data['auth_id']);
        if (!isset($data['id'])) {

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {
                
                if(isset($data['email'])){
                    $user = $this->user->where('email', $data['email'])->first();
                    if(isset($user->id)){
                        $data['user_id'] = $user->id;
                    }
                }
                
                if(!isset($data['user_id'])){
                    return $this->setResponse([ 
                        'code'  => 500,
                        'title' => "User ID is not set. | Email is not registered",
                        'parameters' => $data
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

        // check for duplicate schedules
        $does_exist = $this->agent_schedule
            ->where('user_id', $data['user_id'])
            ->where('title_id', $data['title_id'])
            ->where('start_event', $data['start_event'])
            ->where('end_event', $data['end_event'])
            ->first();

        // existence check

        // insertion
        if (isset($data['cluster'])) {

            $user_cluster = $this->user->where('email', $data['cluster'])->first();
            $access_hierarchy = $this->access_level->where('child_id', $data['user_id'])->first();
            if ($access_hierarchy) {
                $arr = [
                    "id" => $access_hierarchy->id,
                    "parent_id" => $user_cluster->id,
                    "child_id" => $data['user_id']
                ];
            }
            else {
                $arr = [
                    "parent_id" => $user_cluster->id,
                    "child_id" => $data['user_id']
                ];
            }
                $this->access_level_repo->defineAccessLevelHierarchy($arr);

        }

        if (isset($data['id'])) {
            $agent_schedule = $this->agent_schedule->find($data['id']);
        } else if ($does_exist){
            $agent_schedule = $does_exist;
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

        if ( isset($auth_id) ||
            !is_numeric($auth_id) ||
            $auth_id <= 0 )
        {
            $logged_in_user = $this->user->find($auth_id);
            $current_employee = isset($data['user_id']) ? $this->user->find($data['user_id']) : $this->user->find($agent_schedule->user_id);
            if (!$logged_in_user) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User ID is not available.",
                ]);
            }
            
            $logged_data = [
                "user_id" => $auth_id,
                "action" => "POST",
                "affected_data" => "Successfully created a schedule for ".$current_employee->full_name."[".$current_employee->access->name."] on ".$data['start_event']." to ".$data['end_event']." via excel upload by ".$logged_in_user->full_name." [".$logged_in_user->access->name."]."
            ];
            $this->logs->logsInputCheck($logged_data);
        }

        // insertion
        
        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $auth_id,
            'recipient_id' => isset($data['user_id']) ? $data['user_id'] : $agent_schedule->user_id,
            'type' => 'schedules.assign',
            'type_id' => $agent_schedule->id
        ]);

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an agent schedule.",
            "parameters" => $agent_schedule,
        ]);

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

    public function agentScheduleStats($data)
    {
        $result = $this->agent_schedule;
        
        $meta_index = "agent_schedules";

        $sparkline = array();

        $title = null;

        if(isset($data['filter'])) {
            $parameters = [
                'filter' => $data['filter']
            ];
        } else {
            $parameters = [];
        }

        $data['columns'] = ['agent_schedules.*'];
        $data['where'] = array();
        $data['where_between'] = array();
        $data['no_all_method'] = true;
        $data['relations'] = ['user_info'];

        if(isset($data['filter']) && $data['filter'] === 'working') {

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Working.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'end_event',
                'operator' => '>=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
            ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_working', 1);
            }

        } else if(isset($data['filter']) && $data['filter'] === 'absent') {

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Absent.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'agent_schedules.start_event',
                'operator' => '<=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'agent_schedules.end_event',
                'operator' => '>=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
            ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_present', 0);
            }

        } else if(isset($data['filter']) && $data['filter'] === 'off-duty') {

            $result = $this->user;

            $data['columns'] = ['id', 'uid', 'access_id'];

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $title = "Agent Off-Duty.";
            
            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_agent', 1)->where('has_schedule', 0);
            }

        } else if(isset($data['filter']) && $data['filter'] === 'on-break') {

            $title = "Agent on break.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'end_event',
                'operator' => '>=',
                'value' => Carbon::now()
            ]));

            $result = $this->fetchGeneric($data, $result);

            if ($result) {
                $result = $result->where('is_present', 1)->where('is_working', 0);
            }

        } else if(isset($data['filter']) && $data['filter'] === 'on-leave') {

            $title = "Agent on leave.";

            $sparkline = $this->sparkline($data, $result, $data['filter']);

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'end_event',
                'operator' => '>=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'title_id',
                'operator' => '!=',
                'value' => 1
            ]
        ));

            $result = $this->fetchGeneric($data, $result);

        } else {

            $sparkline = $this->sparkline($data, $result);

            $title = "Agent Scheduled.";

            $data['where'] = array_merge($data['where'], array([
                'target' => 'start_event',
                'operator' => '<=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'end_event',
                'operator' => '>=',
                'value' => Carbon::now()
            ],
            [
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
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
                    "count" => $result->count()
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
                "count"     => $result->count()
            ],
            "parameters" => $parameters,
        ]);
    }

    private function sparkline($data, $result, $filter = null) {

        $sparkline = array();

        $now = Carbon::now();

        $previous = Carbon::now()->subDays(9)->format('Y-m-d');
        
        $period = CarbonPeriod::create($previous, $now->format('Y-m-d'))->toArray();

        $now = $now->addDays(1)->format('Y-m-d');

        if($filter === 'working') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now]
            ])); 
            
            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
            ])); 

            $data['relations'] = array('attendances');

            $count_attr = 'attendances';

            $only_attr = ['date', 'attendances'];
        } else if($filter === 'on-leave') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now]
            ])); 

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '!=',
                'value' => 1
            ])); 

            $only_attr = ['start_event', 'end_event'];
        } else if($filter === 'off-duty') {
            $data['relations'] = array('schedule' => function($query) use ($previous, $now){
                $query->whereBetween('start_event', [$previous, $now]);
            });

            $only_attr = ['schedule'];

            $result = $this->fetchGeneric($data, $result)->where('is_agent', 1);
        } else if($filter === 'absent') {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now]
            ])); 
            
            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
            ])); 

            $count_attr = 'attendances';

            $only_attr = ['date', 'attendances', 'start_event', 'end_event'];
        } else {
            $data['where_between'] = array_merge($data['where_between'], array([
                'target' => 'start_event',
                'value' => [$previous, $now]
            ])); 

            $data['where'] = array_merge($data['where'], array([
                'target' => 'title_id',
                'operator' => '=',
                'value' => 1
            ])); 

            $only_attr = ['start_event', 'end_event'];
        }

        if($filter !== 'off-duty') {
            $result = $this->fetchGeneric($data, $result);
        }

        if ($result) {
            $result = $result->map(function ($result) use ($only_attr){
                return collect($result->toArray())
                    ->only($only_attr)
                    ->all();
            });

            foreach ($period as $key => $date) {
                $count = 0;

                foreach ($result as $resKey => $value) {

                    $dates = array();

                    if($filter === 'working') {

                        if(!empty($value[$count_attr])) {

                            $temp = array();

                            $time_in = Carbon::parse($value[$count_attr][0]['time_in'])->format('Y-m-d');
    
                            $time_out = ($value[$count_attr][count($value[$count_attr]) - 1]['time_out']) ? Carbon::parse($value[$count_attr][count($value[$count_attr]) - 1]['time_out'])->format('Y-m-d') : Carbon::now()->format('Y-m-d');

                            $dates = CarbonPeriod::create($time_in, $time_out);

                        }

                        foreach ($dates as $filtered_date) {
                            if(Carbon::parse($filtered_date->format('Y-m-d'))->equalTo($date)) {
                                $count += 1;
                            }
                        }

                    } else if($filter === 'on-leave') {

                        $emp_sched = array();

                        $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                        $end = Carbon::parse($value['end_event'])->format('Y-m-d');

                        $periods = CarbonPeriod::create($start, $end);

                        foreach ($periods as $period) {
                            $emp_sched[] = $period->format('Y-m-d');
                        }

                        if(in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
                            $count += 1;
                        }

                    } else if($filter === 'absent'){

                        $temp = array();

                        $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                        $end = ($value['end_event']) ? Carbon::parse($value['end_event'])->format('Y-m-d') : Carbon::now()->format('Y-m-d');

                        $dates = CarbonPeriod::create($start, $end);

                        $value[$count_attr] = (count($value[$count_attr]) > 0) ? 0 : 1;

                        foreach ($dates as $filtered_date) {
                            if(Carbon::parse($filtered_date->format('Y-m-d'))->equalTo($date)) {
                                $count += $value[$count_attr];
                            }
                        }

                    } else if($filter === 'off-duty') {

                        $emp_sched = array();

                        foreach ($value['schedule'] as $key => $value) {
                            $start = Carbon::parse($value['start_event'])->format('Y-m-d');

                            $end = Carbon::parse($value['end_event'])->format('Y-m-d');

                            $periods = CarbonPeriod::create($start, $end);

                            foreach ($periods as $period) {
                                $emp_sched[] = $period->format('Y-m-d');
                            }
                        }

                        if(!in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
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
    
                        if(in_array(Carbon::parse($date)->format('Y-m-d'), $emp_sched)) {
                            $count += 1;
                        }
    
                    }
                }

                array_push($sparkline, $count);
            }

            $result = $sparkline;
        } 

        if(!$result) {
            $result = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        }

        return $result;
    }

    public function workInfo($data, $option) {

        $result = $this->user;
        
        $meta_index = "agent_schedules";

        $sparkline = array();

        $parameters = array();

        if($option === 'today') {

            $title = "Today's Activity";

            $data['relations'] = array('schedule' => function($query) {
                $query->where('start_event', '<=', Carbon::now());
                $query->where('end_event', '>=', Carbon::now());
            });

        } else if($option === 'report') {

            if(isset($data['start']) && isset($data['end'])) {

                $title = "Work Reports (".$data['start']." to ".$data['end'].")";

                $parameters = [
                    'start' => $data['start'],
                    'end' => $data['end']
                ];

                if(isset($data['userid'])) {
                    $parameters = [
                        'userid' => $data['userid'],
                        'start' => $data['start'],
                        'end' => $data['end']
                    ];

                    $data['where'] = array([
                        'target' => 'id', 
                        'operator' => '=', 
                        'value' => $data['userid']
                    ]);
                }

                $data['relations'] = array('schedule' => function($query) use ($parameters){
                    $end = Carbon::parse($parameters['end']);
                    $end = ($end->isToday()) ? Carbon::now() : $end->addDays(1);
    
                    $query->where('start_event', '>=', Carbon::parse($parameters['start']));
                    $query->where('end_event', '<', $end);
                });

            } else {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Required parameters are not set.",
                    "meta" => [
                        $meta_index => null,
                        "count" => null
                    ],
                    "parameters" => $parameters,
                ]);
            }

        }

        $data['columns'] = ['users.*'];
        $data['no_all_method'] = true;

        $data['wherehas'] = array([
            'relation' => 'access',
            'target' => 'code',
            'value' => 'representative_op'
        ]);

        $result = $this->fetchGeneric($data, $result);

        if ($result == null) {
            return $this->setResponse([
                "code" => 404,
                "title" => "No activities found at the moment",
                "meta" => [
                    $meta_index => $result,
                    "count" => $result->count()
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => $title,
            "meta" => [
                $meta_index => $result,
                "count"     => $result->count()
            ],
            "parameters" => $parameters,
        ]);
    }
}