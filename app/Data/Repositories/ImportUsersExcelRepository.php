<?php

namespace App\Data\Repositories;

ini_set('max_execution_time', 1000);
ini_set('memory_limit', '500M');

use App\Data\Models\AgentSchedule;
use App\Data\Models\AccessLevel;
use App\Data\Models\AccessLevelHierarchy;
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
    $user,$user_datum,$user_benefits,
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
        UserBenefit $user_benefits
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
    }

    public function excelImportUser($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $userInfo = [];
        $user = [];
        $benefits = [];
        $parameters = [];
        $position = $this->genericSearch($data, $this->access_level)->get()->all();
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
                    $parent = UserInfo::where(DB::raw('concat(firstname," ",lastname)') , 'LIKE' , '%'.$firstPage[$x + 1][12].'%')->get();
                    if($parent!='[]'){
                        $parent_id=$parent[0]->id;
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
                        "password"=> bcrypt($firstPage[$x + 1][1]. $firstPage[$x + 1][3]),
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
                    $user_bene['id_number']=$value;
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
            
           
            
      
         return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Uploaded  a User",
            "description" => "Import User Success!",
            "meta"        => [
                "data"        => $data,
            ],
           
           
        ]);
    }else{
        if($data['access_id']!='invalid position'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "Invalid Position",
                "description" => "Add Failed",
                "meta"        => [
                    "error"        =>"Invalid Position",
                ],
               
               
            ]);
        }else if($data['parent_id']!='No Parent Found'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "No Parent Found",
                "description" => "Add Failed",
                "meta"        => [
                    "error"        => "No Parent Found",
                ],
               
               
            ]);
        }else if($data['status']!='Invalid Status'){
            return $this->setResponse([
                "code"       => 404,
                "title"      => "Invalid Status",
                "description" => "Add Failed",
                "meta"        => [
                    "error"        => "Invalid Status",
                ],
               
               
            ]);
        }
       
    }
    }
    
}
