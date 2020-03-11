<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 1000);
ini_set('memory_limit', '500M');

use App\Data\Models\AgentSchedule;
use App\Data\Models\AccessLevel;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\HierarchyLog;
use App\Data\Models\EventTitle;
use App\Data\Models\UserInfo;
use App\Data\Models\UsersData;
use App\Data\Models\Users;
use App\Data\Models\UserBenefit;
use App\Data\Models\UpdateStatus;
use App\Data\Models\UserStatus;
use App\Data\Models\OvertimeSchedule;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\ClusterRepository;
use App\Data\Repositories\ExcelRepository;
use App\Data\Repositories\LogsRepository;
use App\Data\Repositories\NotificationRepository;
use App\Services\ExcelDateService;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\User;
use Auth;
use ArrayObject;
use DB;

class ImportUsersExcelRepository extends BaseRepository
{

    protected $user_benefit,
    $excel_date,
    $user,$user_datum,$user_benefits,$logs, $hierarchy_log,
    $user_info,$access_level,$access_level_hierarchy,$user_status,$user_infos,$status_list;

    public function __construct(
        UserBenefit $user_benefit,
        User $user,
        Users $user_datum,
        ExcelDateService $excelDate,
        UsersData $user_info,
        UserStatus $status_list,
        UserInfo $user_infos,
        AccessLevel $access_level,
        AccessLevelHierarchy $access_level_hierarchy,
        UpdateStatus $user_status,
        LogsRepository $logs_repo,
        UserBenefit $user_benefits,
        HierarchyLog $hierarchy_log
    ) {
        $this->user_benefit = $user_benefit;
        $this->user = $user;
        $this->user_info = $user_info;
        $this->user_infos = $user_infos;
        $this->excel_date = $excelDate;
        $this->user_datum = $user_datum;
        $this->access_level = $access_level;
        $this->access_level_hierarchy = $access_level_hierarchy;
        $this->user_status = $user_status;
        $this->user_benefits = $user_benefits;
        $this->status_list = $status_list;
        $this->logs = $logs_repo;
        $this->hierarchy_log = $hierarchy_log;
    }

    public function excelImportUser($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $userInfo = [];
        $user = [];
        $benefits = [];
        $parameters = [];
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        $position =  $result = $this->fetchGeneric($data, $this->access_level);
        $stat = $this->genericSearch($data, $this->status_list)->get()->all();
        
        $firstPage = $excel[0];
        for ($x = 0; $x < count($firstPage); $x++) {
            $access_id = "invalid position";
            $status = "Invalid Status";
            $parent_id = "No Parent Found";
            $separation_date = null;
            $hired_date=null;
            $birthdate=null;
            if (isset($firstPage[$x + 1])) {
                if ($firstPage[$x + 1][1] != null) {  
                    $parent = DB::table('users')->where('email', $firstPage[$x + 1][12])->get();
                    // $parent = User::where(DB::raw('concat(firstname," ",lastname)') , 'LIKE' , '%'.$firstPage[$x + 1][12].'%')->get();
                   
                    if($parent!='[]'){
                        $parent_id=$parent[0]->uid;
                    }
                    foreach ($position as $key => $value) {
                        if(strtolower($firstPage[$x + 1][11])==strtolower($value->name)){
                        $access_id=$value->id;
                        }
                    }
                    foreach ($stat as $key => $value) {
                    if(strtolower($firstPage[$x + 1][13])==strtolower($value->type)){
                        $status=$value->status;
                    }
                    }
                        
                        array_push($benefits,strval($firstPage[$x + 1][16]));
                        array_push($benefits,strval($firstPage[$x + 1][17]));
                        array_push($benefits,strval($firstPage[$x + 1][18]));  
                        array_push($benefits,strval($firstPage[$x + 1][19]));    
                    if($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][21])!=null){
                        $separation_date=date("m/d/Y", strtotime($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][21])));
                    }
                    if($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][20])!=null){
                        $hired_date=date("m/d/Y", strtotime($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][20])));
                    }
                    if($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][6])!=null){
                        $birthdate=date("m/d/Y", strtotime($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][6])));
                    }
                    
                    // $password_combi = strtolower($firstPage[$x + 1][1]. $firstPage[$x + 1][3]);
                    // $password_combi = str_replace('ñ','n',$password_combi);
                    // $password = trim(preg_replace('/[^A-Za-z0-9-]/', '', $password_combi)," ");
                    $password = "123456";
                    $userInfo[] = array(
                        "firstname" => $firstPage[$x + 1][1],
                        "middlename" => $firstPage[$x + 1][2],
                        "lastname" => $firstPage[$x + 1][3],
                        "suffix" => $firstPage[$x + 1][4],
                        "gender" => $firstPage[$x + 1][5],
                        "birthdate" => date("m/d/Y", strtotime($this->excel_date->excelDateToPHPDate($firstPage[$x + 1][6]))),
                        "address" => $firstPage[$x+1][7],
                        "salary" => $firstPage[$x+1][15],
                        "p_email" => $firstPage[$x + 1][8],
                        "contact_number" => $firstPage[$x + 1][10],
                        "type" => $firstPage[$x + 1][13],
                        "status" => $status,
                        "hired_date" => $hired_date,
                        "separation_date" =>  $separation_date,
                        //"status_reason" => $firstPage[$x + 1][19],
                        "excel_hash" =>  strtolower($firstPage[$x + 1][1]. $firstPage[$x + 1][2]. $firstPage[$x + 1][3]. $firstPage[$x + 1][4]),
                        "email"=> $firstPage[$x + 1][9],
                        "password"=> bcrypt($password),
                        "company_id"=> $firstPage[$x + 1][0],
                        "contract"=> $firstPage[$x + 1][14],
                        "login_flag"=> 0,
                        "access_id"=> $access_id,
                        "parent_id" =>$parent_id,
                        "benefits" => $benefits
                    );   
                }
            }
            $benefits = [];
            
        };
        //$userInfo['auth_id'] = auth::id();
        $logged_data = [
            "user_id" => $auth->id,
            "action" => "Create",
            "affected_data" => $auth->full_name."[".$auth->access->name."] Uploaded An Excel File to Import Users."
        ];
        $this->logs->logsInputCheck($logged_data);

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Uploaded Users",
            "description" => "Import User Success!",
            "meta"        => [
                "user" =>$userInfo,
                'auth_id' => auth::id(),
            ],
            
            'parameter' =>$parameters
            
        ]);
        // $result = $this->addUser($userInfo);
        // return $result;

    }

    public function addUser($data = [])
    {   
        // data validation
        $error_array =  new ArrayObject();
        $error_count=0;
        $action=null;
        $user_data=[];
        $user_information = [];
        $hierarchy = [];
        $cluster=[];
        $user_benefits=[];
        $benefits=[];
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);

        if($data['access_id']!='invalid position'&&$data['parent_id']!='No Parent Found'&&$data['status']!='Invalid Status'){
            $user_information['firstname']= $data['firstname'];
            $user_information['middlename']= $data['middlename']; 
            $user_information['lastname']= $data['lastname'];
            $user_information['birthdate']= $data['birthdate'];
            $user_information['gender']= $data['gender'];   
            $user_information['contact_number']= $data['contact_number'];
            $user_information['address']= $data['address'];
            $user_information['status']= $data['status'];
            $user_information['hired_date']= $data['hired_date'];
            $user_information['separation_date']= $data['separation_date'];
            $user_information['excel_hash']= $data['excel_hash'];
            $user_information['p_email']= $data['p_email'];
            $user_information['salary']= $data['salary'];
           // $user_information['status_reason']= $data['status_reason'];
          
            $user_informations =  $this->user_infos->init($this->user_infos->pullFillable($user_information));
                if (!$user_informations->save($data)) {
                    if (strpos($user_informations->errors(), 'user_infos_excel_hash_unique') !== false) {
                        $error_array->offsetSet(1, "Full Name is already in Use.");
                        $error_count++;
                        
                    }else{
                        $error_array->offsetSet(2,  $user_informations->errors());
                        $error_count++;
                        
                    }
                } 
                
            $user_id = $user_informations->id;

            if($error_count>0){
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                 $user_info_delete->forceDelete();
                }
                return $this->setResponse([
                    "code"       => 404,
                    "title"      => $error_array[1],
                    "description" => "Add Failed",
                    "meta"        => [
                        "data"        => $data,
                        "error"        => $error_array,
                    ],
                   
                   
                ]);
             }     
          
            $hierarchy['child_id']= $user_id;
            $hierarchy['parent_id']= $data['parent_id'];
        
            $user_hierarchy= $this->access_level_hierarchy->init($this->access_level_hierarchy->pullFillable($hierarchy));
            // insert hierarchy log
            $hierarchy_log = $this->hierarchy_log;
            $hierarchy_log->parent_id = $hierarchy['parent_id'];
            $hierarchy_log->child_id = $hierarchy['child_id'];
            $hierarchy_log->start_date = Carbon::parse($user_information['hired_date'])->startOfDay();
            $hierarchy_log->save();


            $user_data['uid']= $user_id;
            $user_data['email']= $data['email'];
            $user_data['access_id']= $data['access_id'];
            $user_data['company_id']= $data['company_id'];
            $user_data['contract']= $data['contract'];
            $user_data['login_flag']= $data['login_flag'];
            $user_data['password'] = $data['password'];
            $users_data = $this->user_datum->init($this->user_datum->pullFillable($user_data));
            $status_logs['user_id']=$user_id;
            $status_logs['status']=$data['status'];
            $status_logs['type']=$data['type'];
            $status_logs['hired_date']=$data['hired_date'];
            $status = $this->user_status->init($this->user_status->pullFillable($status_logs)); 
            $action="Created";  
            if (!$status->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                $error_array->offsetSet(1, "Saving Error Status");
                $error_count++;       
            }
            if (!$user_hierarchy->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                $error_array->offsetSet(1, "Saving Error Hierarchy");
                $error_count++;  
            }   

            if (!$users_data->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                if (strpos($users_data->errors(), 'users_email_unique') !== false) {
                    $error_array->offsetSet(1, "Email is Already In Use.");
                    $error_count++;  
                }
               
            } 
        

            if($data['benefits']==[]){
                for($i=1; $i<5;$i++ ){
                    $ben['benefit_id'] = $i;
                    $ben['id_number'] = NULL;
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));   
                    array_push($benefits,$user_ben);
                    $user_ben->save();   
            }   
            }else{
                foreach($data['benefits'] as $key => $value ){
                   
                    $ben['benefit_id'] = $key+1;
                    $ben['id_number']=$value;
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));   
                    array_push($benefits,$user_ben);
                    $user_ben->save();   
            }       
            }
           
            if($error_count>0){
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                 $user_info_delete->forceDelete();
                }
                return $this->setResponse([
                    "code"       => 404,
                    "title"      => $error_array[1],
                    "description" => "Add Failed",
                    "meta"        => [
                        "data"        => $data,
                        "error"        => $error_array,
                    ],
                   
                   
                ]);
             }
            
           
            
        $logged_data = [
        "user_id" => $auth->id,
        "action" => "Create",
        "affected_data" => $auth->full_name."[".$auth->access->name."] Added a User [".$user_informations->full_name."] via Excel Upload."
        ];
        $this->logs->logsInputCheck($logged_data);
         return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Uploaded  a User",
            "description" => "Import User Success!",
            "meta"        => [
                "data"        => $data,
            ],
           
           
        ]);
    }else{
        if($data['access_id']=='invalid position'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "Invalid Position",
                "description" => "Add Failed",
                "meta"        => [
                    "data"        => $data,
                    "error"        =>"Invalid Position",
                ],
               
               
            ]);
        }else if($data['parent_id']=='No Parent Found'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "No Parent Found",
                "description" => "Add Failed",
                "meta"        => [
                    "data"        => $data,
                    "error"        => "No Parent Found",

                ],
               
               
            ]);
        }else if($data['status']=='Invalid Status'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "Invalid Status",
                "description" => "Add Failed",
                "meta"        => [
                    "data"        => $data,
                    "error"        => "Invalid Status",
                ],
               
               
            ]);
        }
       
    }
    }

    public function excelImportUserV2($data = []){
        $report=[];
        $fields= $data[0];
        foreach($data as $k => $datum){
            $row_error="";
            if($k > 0){

                // validate null fields and email fields
                $error_row = $this->validateUserRow($datum, $fields);
                $report[$k-1] = $datum;
                if($error_row!=""){
                    $report[$k-1]["import_result"] =[
                        "code" => "500",
                        "action" => "validating",
                        "description" => $error_row
                    ];
                }else{
                    $user = $this->user->where("email", $datum[9])->first();
                    $action = $user==null?"create":"update";
                    $info_id = $user?$user->uid:null;

                    // saving info details
                    $save_info = $this->saveInfo($datum,$info_id);

                    if(!$save_info){
                        $report[$k-1]["import_result"]=$this->importRowError("info",$action);
                    }

                    // saving user details
                    $save_user = $this->saveUser($datum, $save_info);

                    if(!$save_user){
                        if(!$user){
                            $this->user_infos->find($save_info)?$this->user_infos->find($save_info)->delete():null;
                        }
                        $report[$k-1]["import_result"]=$this->importRowError("user",$action);
                    }

                    $save_hierarchy = $this->saveHierarchy($datum, $save_info);

                    if(!$save_hierarchy){
                        if(!$user){
                            $this->user_infos->find($save_info)?$this->user_infos->find($save_info)->delete():null;
                            $this->user->where("uid", $save_info)->first()?$this->user->where("uid", $save_info)->first()->delete():null;
                        }
                        $report[$k-1]["import_result"]=$this->importRowError("hierarchy",$action);
                    }

                    $save_hierarchy_log = $this->saveHierarchyLog($datum, $save_info);

                    if(!$save_hierarchy_log){
                        if(!$user){
                            $this->user_infos->find($save_info)?$this->user_infos->find($save_info)->delete():null;
                            $this->user->where("uid", $save_info)->first()? $this->user->where("uid", $save_info)->first()->delete():null;
                            $this->access_level_hierarchy->where("child_id", $save_info)->first()?$this->access_level_hierarchy->where("child_id", $save_info)->first()->delete():null;
                        }
                        $report[$k-1]["import_result"]=$this->importRowError("hierarchy_log",$action);
                    }

                    $save_benefits = $this->saveBenefits($datum, $save_info);

                    if(!$save_benefits){
                        if(!$user){
                            $this->user_infos->find($save_info)?$this->user_infos->find($save_info)->delete():null;
                            $this->user->where("uid", $save_info)->first()?$this->user->where("uid", $save_info)->first()->delete():null;
                            $this->access_level_hierarchy->where("child_id", $save_info)->first()?$this->access_level_hierarchy->where("child_id", $save_info)->first()->delete():null;
                            $this->hierarchy_log->where("child_id", $save_info)->first()?$this->hierarchy_log->where("child_id", $save_info)->first()->delete():null;
                            $this->user_benefits->where("user_info_id", $save_info)->first()? $this->user_benefits->where("user_info_id", $save_info)->delete():null;
                        }
                        $report[$k-1]["import_result"] = $this->importRowError("benefit",$action);
                    }else{
                        $report[$k-1]["import_result"] = [
                            "code" => "200",
                            "action" => $action,
                            "description" => "success"
                        ];
                    }
                }
            }    
        }
        return $this->setResponse([
            "code"       => 200,
            "title"      => "Import employees v2",
            "description" => "Import Status",
            "meta"        => [
                "data"        => $data,
                "report"        => $report,
            ],
        ]);
    }
    
    public function validateUserRow($data=[],$fields=[]){
        $required_index = [1,2,3,5,6,7,8,9,11,12,13,20];
        $email_index = [9,12];
        $error="";
        foreach($required_index as $required){
            $error = $this->validateRequired($data[$required], $fields[$required]);
        }

        if($error==""){
            foreach($email_index as $required){
                $error = $this->validateEmail($data[$required], $fields[$required]);
            }
        }

        if($error==""){
            $error = $this->validatePosition($data[11]);
        }

        if ($error == "") {
            $error = $this->validateDates([$data[6], $data[20], $data[21]]);
        }
        
        if($error==""){
            // company email must not equal to supervisor email
            if($data[9] == $data[12]){
                $error = "Invalid supervisor input.";
            }
        }

        // validate supervisor email
        $head = $this->validateEmailExistence($data[12]);
        if($error==""){
            if(!$head){
                $error = "Supervisor email not found.";
            }
        }

        return $error;
    }

    public function validateEmail($email, $prop){
        $error = "";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = $prop." is an invalid email format.";
        }
        return $error;
    }

    public function validateRequired($data, $prop){
        $error = "";
        if($data==null){
            $error = $prop."Field is required.";
        }
        return $error;
    }

    public function validatePosition($position){
        $error="";
        if(!$this->access_level->where("name","like","%".$position."%")->first()){
            $error = "Position Invalid";
        }
        return $error;
    }

    public function validateDates($dates)
    {
        try {
            foreach ($dates as $date) {
                Carbon::parse($date)->format("m/d/Y");
            }
        } catch (\Exception $e) {
            return "Invalid date format";
        }
    }

    public function validateEmailExistence($email){
        return $this->user->where("email", $email)->first();
    }


    public function saveInfo($data,$info_id){
        $info = [
            "firstname" => $data[1],
            "middlename"=> $data[2],
            "lastname"=> $data[3],
            "suffix"=> $data[4],
            "birthdate"=> $data[6]?Carbon::parse($data[6])->format("m/d/Y"):null,
            "gender"=> $data[5],
            "contact_number"=> $data[10],
            "address"=> $data[7],
            "salary_rate"=> doubleval($data[15]),
            "status"=> $this->getStatusByType($data[13]),
            "type"=> $data[13],
            "hired_date"=> Carbon::parse($data[20])->format("m/d/Y"),
            "separation_date"=> $data[21]?Carbon::parse($data[21])->format("m/d/Y"):null,
             "excel_hash"=> strtolower(str_replace(" ", "",$data[1].$data[2].$data[3].$data[4])),
             "p_email"=> $data[8],
            //  "status_reason"=> $data[1]
            ];
        $user_info = ($info_id ? $this->user_infos->find($info_id) : $this->user_infos->init($this->user_infos->pullFillable($info)));
        return $user_info->save($info)?$user_info->id:null;
    }

    public function saveUser($data, $info_id){
        $user = $this->user->where("uid",$info_id)->first();
        $row=[
            "email" => $data[9],
            "company_id" => $data[0],
            "contract" => $data[14],
            "access_id" => $this->access_level->where("name","like","%".$data[11]."%")->first()->id,
        ];
        if(!$user){
            // create
            $row["uid"] = $info_id;
            $row["password"] = bcrypt("123456");
            $user = $this->user_datum->init($this->user_datum->pullFillable($row));
        }
        return $user->save($row)? $info_id:null;
    }

    public function saveHierarchy($data, $info_id){
        $hierarchy = $this->access_level_hierarchy->where("child_id",$info_id)->first();
        $row = [
            "child_id" => $info_id,
            "parent_id" => $this->user->where("email", $data[12])->first()->uid,
        ];
        if(!$hierarchy){
            $hierarchy = $this->access_level_hierarchy->init($this->access_level_hierarchy->pullFillable($row));
        }

        return $hierarchy->save($row)? $info_id:null;
    }

    public function saveHierarchyLog($data, $info_id){
        $hierarchy_log = $this->hierarchy_log->where("child_id",$info_id)->first();
        $row = [
            "child_id" => $info_id,
            "parent_id" => $this->user->where("email", $data[12])->first()->uid,
            'start_date' => Carbon::parse($data[20])->format("Y-m-d H:i:s")
        ];
        if(!$hierarchy_log){
            $hierarchy_log = $this->hierarchy_log->init($this->hierarchy_log->pullFillable($row));
        }

        return $hierarchy_log->save($row)? $info_id:null;
    }

    public function saveBenefits($data, $info_id){
        $error_count=0;
        $user_benefits = $this->user_benefits->where("user_info_id",$info_id)->first();
        $benefit_index = [16,17,18,19];
        if(!$user_benefits){
            foreach($benefit_index as $k => $index){
                $row = [
                    "user_info_id" => $info_id,
                    "id_number" => $data[$index],
                    "benefit_id" => $k+1
                ];
                $benefit = $this->user_benefits->init($this->user_benefits->pullFillable($row));
                if(!$benefit->save($row)){
                    $error_count = 1;
                }
            }
        }else{
            foreach($benefit_index as $k => $index){
                $row = [
                    "id_number" => $data[$index],
                    "benefit_id" => $k+1
                ];

                $benefit = $this->user_benefits->where("user_info_id", $info_id)->where("benefit_id", $k+1)->first();

                if(!$benefit->save($row)){
                    $error_count = 1;
                }
            }
        }

        return $error_count?null:$info_id;
    }

    public function getStatusByType($type){
        $status = "";
        switch(strtolower($type)){
            case "terminated":
            case "resigned":
            case "suspended":
            $status = "inactive";
            break;
            default:
            $status = "active";
            break;
        }
        return $status;
    }

    public function importRowError($section, $action){
        return ["code" => "500", "action"=>$action,"description" => "Something is wrong upon ". ($action == "create"? "creating": "updating") ." the data. section: ".$section];
    }
}
