<?php

namespace App\Data\Repositories;

use App\Data\Models\HierarchyLog;
use App\Data\Models\UserInfo;
use App\User;
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
    $user,
    $logs,
    $hierarchy_log,
    $notification_repo;

    public function __construct(
        UserInfo $userInfo,
        User $user,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository,
        HierarchyLog $hierarchy_log
    ) {
        $this->user_info = $userInfo;
        $this->user = $user;
        $this->hierarchy_log = $hierarchy_log;
        $this->logs = $logs_repo;
        $this->notification_repo = $notificationRepository;
        // $this->no_sort = [
        //     'parent_details.full_name',
        //     'child_details.full_name',
        // ];
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

        // filter active
        $data['wheredoesnthave'][] = [
            'relation' => 'parent_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

        $data['wheredoesnthave'][] = [
            'relation' => 'child_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

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

        if(!isset($data["parent_id"]) && !isset($data["parent_email"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "Head(parent_id) field is required.",
                "meta" => [],
                "parameters" => $data,
            ]);
        }

        if(isset($data["parent_email"])){
            $parent = User::where('email',$data["parent_email"])->first();
            if(!$parent){
                return $this->setResponse([
                    "code" => 422,
                    "title" => "Head(parent_id) field is required.",
                    "meta" => [],
                    "parameters" => [
                        "user_input" => $data,
                        "email" => $data["parent_email"]
                    ],
                ]);
            }
            $data["parent_id"] = $parent["uid"];
        }
        
        if(!isset($data["child_id"])  && !isset($data["child_email"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "Subordinate(child_id) field is required.",
                "meta" => [],
                "parameters" => $data,
            ]);
        }

        
        if(isset($data["child_email"])){
            $child = User::where('email',$data["child_email"])->first();
            if(!$child){
                return $this->setResponse([
                    "code" => 422,
                    "title" => "Subordinate(child_id) field is required.",
                    "meta" => [],
                    "parameters" => [
                        "user_input" => $data,
                        "email" => $data["child_email"]
                    ],
                ]);
            }
            $data["child_id"] = $child["uid"];
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
                "parameters" => [
                    "user_input" => $data,
                    "email" => $this->user->where("uid", $data["child_id"])->first()->email
                ],
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
            "parameters" => [
                "user_input" => $data,
                "email" => $this->user->where("uid", $data["child_id"])->first()->email
            ],
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
            "parameters" => [
                "user_input" => $data,
                "email" => $this->user->where("uid", $data["child_id"])->first()->email
            ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined hierarchy.",
            "meta" => $response,
            "parameters" => [
                "user_input" => $data,
                "email" => $this->user->where("uid", $data["child_id"])->first()->email
            ],
        ]);
    }

    // ______________________________________________________________

    public function destroy($data = []){
        // validate if exist
        if(!isset($data["id"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "id parameter is required.",
            ]);
        } 

        $hierarchy_log = $this->hierarchy_log->find($data["id"]);

        if(!$hierarchy_log){
            return $this->setResponse([
                "code" => 422,
                "title" => "id does not exist.",
                "parameters" => $data["id"]
            ]);
        }

        if(!$this->hierarchy_log->destroy($data["id"])){
            return $this->setResponse([
                "code" => 422,
                "title" => "something is wrong! data not deleted.",
                "parameters" => $data["id"]
            ]);
        };

        return $this->setResponse([
            "code" => 200,
            "title" => "data successfully deleted.",
            "meta" => $hierarchy_log,
            "parameters" => $data["id"]
        ]);
    }

    public function scheduleToHLog($schedule = []){
        $save1=null;$save2=null;
        $user["agent"] = $this->validateEmail($schedule["email"],"Agent");
        $user["tl"] = $this->validateEmail($schedule["tl_id"],"Team Leader");
        $user["om"] = $this->validateEmail($schedule["om_id"],"Operations Manager");
        $action="create";
        $result= null;

        if($schedule["email"] && $schedule["tl_id"]){
            if($user["agent"]["result"] && $user["tl"]["result"]){
                $data1 = [
                    "child_id" => $user["agent"]["exists"]["uid"],
                    "parent_id" => $user["tl"]["exists"]["uid"],
                    "start_date" => Carbon::parse($schedule["start_event"])->startOfDay()->format("Y-m-d H:i:s")
                ];
                $save1=$this->defineV2($data1);
            }
        }

        if($schedule["om_id"]){
            if($user["tl"]["result"] && $user["om"]["result"]){
                $data2 = [
                    "child_id" => $user["tl"]["exists"]["uid"],
                    "parent_id" => $user["om"]["exists"]["uid"],
                    "start_date" => Carbon::parse($schedule["start_event"])->startOfDay()->format("Y-m-d H:i:s")
                ];
                $save2=$this->defineV2($data2);
            }
        }else{
            $save2 = true;
        }



        if($save1 && $save2){
            $result = [
                "code" => 200,
                "description" => "Hierarchy ". ($action=="create"? "created.": "updated."),
            ];
        }else{
            if(!$save1){
                $result = [
                    "code" => 500,
                    "description" => "Error on saving hierarchy log. (1)",
                ];
            }
            if(!$save2){
                $result = [
                    "code" => 500,
                    "description" => "Error on saving hierarchy log. (2)",
                ];
            }
        }

        
        $result["details"][] = $save1;
        $result["details"][] = ($save2===true? "no 2nd level.": $save2);

        return $result;
    }

    public function defineV2($data = []){
        $data['start_date'] = Carbon::parse($data['start_date'])->startOfDay()->format("Y-m-d H:i:s");
        $exists = $this->hierarchy_log->where("child_id", $data["child_id"])->where("start_date",$data["start_date"])->first();
        // $next =  $this->hierarchy_log->where("child_id", $data["child_id"])->where("start_date",">",$data["start_date"])->first();
        // $prev =  $this->hierarchy_log->where("child_id", $data["child_id"])->where("start_date","<",$data["start_date"])->first();

        // if($next){
        //     $data["end_date"] = Carbon::parse($next->start_date)->subDays(1)->endOfDay()->format("Y-m-d H:i:s");
        // }else{
        //     $data["end_date"] = null;
        // }


        // redefine end_date value
        $latest = $this->hierarchy_log->where('child_id', $data['child_id'])->whereNull('end_date')->orderBy('start_date','desc')->first();
            // if input start date > latest start date
            if(Carbon::parse($latest->start_date)->isBefore(Carbon::parse($data['start_date']))){
                // asign value to latest end_date
                $latest->end_date = Carbon::parse($latest->start_date)->endOfDay()->format("Y-m-d H:i:s");
                $latest->save((array)$latest);
                // assign end_date value for new instance
                $data['end_date'] = null;
            }else{
                // 
                $data['end_date'] = Carbon::parse($data['start_date'])->endOfDay()->format("Y-m-d H:i:s");
            }
        // save new instance
        $hl = $this->hierarchy_log->init($this->hierarchy_log->pullFillable($data));
        
        return [
            "code" => $hl->save($data)?200:500,
            "exists" => $exists,
            "input" => $data,
            "action" => $exists ? "update":"create",
            "saved" => $hl
        ];
    }

    public function isEmail($email){
        return (filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    public function validateEmail($email, $prop){
        $result = null;
        $result["isEmail"] = $this->isEmail($email); 
        $result["exists"] = $this->user->where("email", $email)->first();

        $error = null;

        !$result["isEmail"] ? $error = $prop." is an invalid email.": ""; 

        if($error==null){
            !$result["exists"]? $error = $prop." does not exist.":"";
        }


        if($error){
            $result["result"] = false;
            $result["description"] = $error;
        }else{
            $result["result"] = true;
            $result["description"] = "validated";
        }
        return $result;
    }

    public function subordinates($data = []){
        $meta_index = "subordinates";
        $parameters = [];
        $count = 0;

        $date = Carbon::parse($data["date"])->format('Ymd');

        // table column filters
        $data["where_null"][]='end_date';
        // filter by parent_id
        if (isset($data['parent_id'])) {
            $data['where'][] = [
                "target" => "parent_id",
                "operator" => "=",
                "value" => $data['parent_id'],
            ];
        }

        
        // filter active
        $data['wheredoesnthave'][] = [
            'relation' => 'parent_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

        $data['wheredoesnthave'][] = [
            'relation' => 'child_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

        
        $data["groupby"] = ["child_id"]; 
        
        $data["order"] = [
            "start_date" => "desc"
        ];
        
        if(isset($data['sort_order']) && isset($data['sort'])){
            $data["order"][$data["sort"]] = $data["sort_order"];
        }else{
            unset($data["sort"]);
        }


        $data["relations"][] = 'parent_details';
        $data["relations"][] = 'child_details';
        $data["relations"][] = 'parent_user_details';
        $data["relations"][] = 'child_user_details';

        // $result = $this->fetchGeneric($data, $this->hierarchy_log);
        $count_data = $data;
        $count_data["count"] = true;
        if(isset($data['query'])){
            $data['target'] = ['child_details.firstname','child_details.middlename','child_details.lastname'];
            $result = $this->genericSearch($data, $this->hierarchy_log)->get()->all();
            $count_data["search"] = true;
            $count = $this->countData($count_data, refresh_model($this->hierarchy_log->getModel()));
        }else{
            $result = $this->fetchGeneric($data, $this->hierarchy_log);
            $count = $this->countData($count_data, refresh_model($this->hierarchy_log->getModel()));
        }

        if(!$result){
            return $this->setResponse([
                'code' => 404,
                'title' => "No Subordinates found.",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved subordinates.",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $data,
        ]);
    }

    
    public function supervisor($data = []){
        $meta_index = "supervisor";
        $parameters = $data;

        if(!isset($data["date"])){
            return $this->setResponse([
                "code" => 401,
                "title" => "date field required.",
                "meta" => null,
                "parameters" => $parameters,
            ]);
        }
        if(!isset($data["child_id"])){
            return $this->setResponse([
                "code" => 401,
                "title" => "child_id field required.",
                "meta" => null,
                "parameters" => $parameters,
            ]);
        }

        $meta_data = null;
        $same_date = $this->hierarchy_log
        ->where("start_date","<=",$data['date'])
        ->where("end_date",">=",$data['date'])
        ->where("child_id",$data["child_id"])
        ->first();
        $latest_date = $this->hierarchy_log
        ->where("start_date","<",$data['date'])
        ->where("child_id",$data["child_id"])
        ->orderBy("start_date","desc")
        ->first();

        $meta_data = $same_date ? $same_date : $latest_date;

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved supervisor.",
            "meta" => [
                $meta_index => $meta_data,
                // "count" => $meta_data?1:0,
            ],
            "parameters" => $parameters,
        ]);
    }


    public function table($data = []){
        $hierarchy_log = $this->hierarchy_log;
        $meta_index = "hierarchy_log";
        $parameters = [];
        $count = 0;

        
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
        }else{
            $data['wherehas'][] = [
                'relation' => 'parent_user_details',
                'target' => [
                    [
                        'column' => 'access_id',
                        'operator' => "wherenotin",
                        'value' => [15,16],
                    ],
                ],
            ];
        }
        // filter date
        if (!isset($data['date'])) {
            $data["date"] = Carbon::parse()->startOfDay();
        }else{
            $data["date"] = Carbon::parse($data['date'])->startOfDay();
        }
        $data["where"][]= [
            "target" => "start_date",
            "operator" => "<=",
            "value" => $data["date"]
        ];

        // filter active
        $data['wheredoesnthave'][] = [
            'relation' => 'parent_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

        $data['wheredoesnthave'][] = [
            'relation' => 'child_details.status_logs',
            'target' => [
                [
                    'column' => 'separation_date',
                    'operator' => "<=",
                    'value' => $data['date'],
                ],
                [
                    'column' => 'type',
                    'operator' => "wherein",
                    'value' => ["Terminated", "Resigned"],
                ],
            ],
        ];

        // search targets
        $data["target"]=["parent_details.firstname", "child_details.firstname"];

        // relations
        $data["relations"][] = 'parent_details';
        $data["relations"][] = 'child_details';
        $data["relations"][] = 'parent_user_details';
        $data["relations"][] = 'child_user_details';
        // orderBy

        $data["order"] = 'desc';

        if(isset($data['sort']) && isset($data['sort_order'])){
            $data["order"] = $data["sort_order"];
        }

        // groupBy
        $data["groupby"] = ["child_id","parent_id"];
        // pagination
        if(isset($data['perpage'])){
            if(!isset($data['page'])){
                $data["page"] = 1;
            }
            $data["limit"] = $data["perpage"];
            $data["offset"] = ($data["page"] - 1) * $data["perpage"];
        }

        if(isset($data['sort'])){
            if($data['sort'] == 'child_details.full_name'){
                $data['sort'] = 'firstname';
                $data['sort_key'] = 'hierarchy_logs.child_id';
            } else {
                $data['sort'] = 'firstname';
                $data['sort_key'] = 'hierarchy_logs.parent_id';
            }

            //join tables for sorting
            $hierarchy_log = $this->hierarchy_log
                ->join('user_infos as child', 'child.id', '=', $data['sort_key'])
                ->orderBy($data['sort'], $data['order'] ?? 'desc');

            unset($data['sort']);
        }

        // final query
        if(isset($data["query"])){
            $result = $this->genericSearch($data, $hierarchy_log)->get()->all();
            $data["count"] = true;
            $data["search"] = true;
        }else{  
            $result = $this->fetchGeneric($data, $hierarchy_log);
            $data['count'] = true;
        }

        $count = $this->countData($data, refresh_model($this->hierarchy_log->getModel()));

        if(!$result){
            return $this->setResponse([
                'code' => 404,
                'title' => "No logs are found",
                "meta" => [
                    $meta_index => array_values($result),
                    "count" => $count
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved hierarchy logs.",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],  
            // "parameters" => $parameters,
            "parameters" => $data,
        ]);
    }
}