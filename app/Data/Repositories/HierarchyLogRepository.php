<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 180);
ini_set('memory_limit', '-1');

use App\Data\Models\HierarchyLog;
use App\Data\Models\UserInfo;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class HierarchyLogRepository extends BaseRepository
{

    protected
    $user_info,
    $logs,
    $hierarchy_log,
    $notification_repo;

    public function __construct(
        UserInfo $userInfo,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository,
        HierarchyLog $hierarchy_log
    ) {
        $this->user_info = $userInfo;
        $this->hierarchy_log = $hierarchy_log;
        $this->logs = $logs_repo;
        $this->notification_repo = $notificationRepository;
    }

    public function fetchAll($data = []){
        $meta_index = "hierarchy_log";
        $parameters = [];
        $count = 0;

        if (!isset($data['date'])) {
            $data["date"] = Carbon::now();
        }

        if(isset($data["id"])&& is_numeric($data["id"])){
            $meta_index = "hierarchy_log";
            $data['single'] = true;
            $data['where'] = [
                "target" => "id",
                "operator" => "=",
                "value" => $data["id"]
            ];

            $parameters['hierarchy_log_id'] = $data["id"];
        }

        // table column filters
        // filter by parent_id
        if (isset($data['parent_id'])) {
            $data['where'][] = [
                "target" => "parent_id",
                "operator" => "=",
                "value" => $data['parent_id'],
            ];
        }
        // filter by child_id
        if (isset($data['child_id'])) {
            $data['where'][] = [
                "target" => "child_id",
                "operator" => "=",
                "value" => $data['child_id'],
            ];
        }
        // filter by start_date
        if (isset($data['start_date'])) {
            $data['where'][] = [
                "target" => "start_date",
                "operator" => "=",
                "value" => $data['start_date'],
            ];
        }
        // filter by end_date
        if (isset($data['end_date'])) {
            $data['where'][] = [
                "target" => "end_date",
                "operator" => "=",
                "value" => $data['end_date'],
            ];
        }
        //  filter by operations dept
        if (isset($data['operations'])) {
            $data['wherehas'][] = [
                'relation' => 'parent_user_details',
                'target' => [
                    [
                        'column' => 'access_id',
                        'operator' => "wherein",
                        'value' => [15,16],
                    ],
                ],
            ];
        }

        // i

        $data["relations"][] = 'parent_details';
        $data["relations"][] = 'child_details';
        $data["relations"][] = 'parent_user_details';
        $data["relations"][] = 'child_user_details';

        $result = $this->fetchGeneric($data, $this->hierarchy_log);

        $result = collect($result)
        ->where('start_date',"<=",Carbon::parse($data['date'])->startOfDay()->toDateTimeString())
        ->where('tmp_end_date',">=",Carbon::parse($data['date'])->startOfDay()->toDateTimeString());

        //  result in distinct list form base on filter_by value
        if (isset($data['list']) && isset($data['filter_by'])) {
            $result = 
            array_values(
                $result->groupBy($data['filter_by'].".full_name")
                ->map(function($i){
                return $i[0];
            })->toArray())
            ;
        }

        
        if(isset($data['target'])) {
            $result = collect($result)->filter(function($v,$i)use($data){
                if(strpos(strtolower($v[$data["filter_by"]]["full_name"]),strtolower($data["query"])) !== false){
                    return $v;
                }
            });
        }

        $count = collect($result)->count();

        if(!$result){
            return $this->setResponse([
                'code' => 404,
                'title' => "No logs are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved hierarchy logs.",
            "meta" => [
                $meta_index => isset($data["page"])? $this->paginate($result,$data["perpage"],$data["page"]):$result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }


    // _______________________________________________________________

    public function define($data = []){

        // column validation

        if(!isset($data["parent_id"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "Head(parent_id) field is required.",
                "meta" => [],
                "parameters" => $data,
            ]);
        }

        if(!isset($data["child_id"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "Subordinate(child_id) field is required.",
                "meta" => [],
                "parameters" => $data,
            ]);
        }

        if(!isset($data["start_date"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "Start field is required.",
                "meta" => [],
                "parameters" => $data,
            ]);
        }

        // initialize response meta
        
        $response = [
            "head" => $this->user_info->find($data["parent_id"]),
            "subordinate" => $this->user_info->find($data["child_id"]),
            "start_date" => $data["start_date"],
            "status" => 1,
            "title" => "uploaded"
        ];

        // child must  not have duplicate start_date
        $start_duplicate = $this->hierarchy_log
        ->where("child_id",$data["child_id"])
        ->where("start_date",$data["start_date"])
        ->first();

        if($start_duplicate){
            $response["status"] = 0;
            $response["title"] = "duplicate start date.";
            return $this->setResponse([
                "code" => 422,
                "title" => "Subordinate has duplicate date.",
                "meta" => $response,
                "parameters" => $data,
            ]);
        }


        // check if child has previous log
        $check = $this->hierarchy_log
        ->where("child_id", $data["child_id"])->first();

        if($check){
            // define end_date for previous log
            // get null log
            $conflict_date = $this->hierarchy_log
            ->where("child_id",$data['child_id'])
            ->where("start_date","<=",$data['start_date'])
            ->where("end_date",">=",$data['start_date'])
            ->whereNotNull("end_date")
            ->first();

            if($conflict_date){
                $response["status"] = 0;
                $response["title"] = "conflict start date.";
                return $this->setResponse([
                    "code" => 422,
                    "title" => "Conflict date field please adjust date.",
                    "meta" => $response,
                    "parameters" => $data,
                ]);
            }

            $null = $this->hierarchy_log
            ->where('child_id',$data["child_id"])
            ->whereNull('end_date')->first();

            $null->end_date = Carbon::parse($data["start_date"])->sub(CarbonInterval::days(1));
            $null->save();

        }
        // check if parent and start date is confict

        $hierarchy_log = $this->hierarchy_log->init($this->hierarchy_log->pullFillable($data));

        if(!$hierarchy_log->save($data)){
            $response["status"] = 0;
            $response["title"] = "something is wrong! data not saved.";
            return $this->setResponse([
                "code" => 500,
                "title" => "There is a problem with the input data.",
                "meta" => $response,
                "parameters" => $data,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined hierarchy.",
            "meta" => $response,
            "parameters" => $data,
        ]);
    }

}