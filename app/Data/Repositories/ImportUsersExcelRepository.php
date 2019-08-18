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

class ImportUsersExcelRepository extends BaseRepository
{

    protected $user_benefit,
    $excel_date,
    $user,$user_datum,$user_benefits,
    $user_info,$access_level,$access_level_hierarchy,$user_status,$user_infos;

    public function __construct(
        UserBenefit $user_benefit,
        User $user,
        Users $user_datum,
        ExcelDateService $excelDate,
        UsersData $user_info,
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
    }

    public function excelImportUser($data)
    {
        $excel = Excel::toArray(new ExcelRepository, $data['file']);
        $userInfo = [];
        $user = [];
        $benefits = [];

        $firstPage = $excel[0];
        for ($x = 0; $x < 1; $x++) {
            if (isset($firstPage[$x + 3])) {
                if ($firstPage[$x + 3][1] != null) {
                     
                       
                         
                           
                            array_push($benefits,strval($firstPage[$x + 1][11]));
                            array_push($benefits,strval($firstPage[$x + 1][12]));
                            array_push($benefits,strval($firstPage[$x + 1][13]));  
                            array_push($benefits,strval($firstPage[$x + 1][14]));      
                      
                        $userInfo[] = array(
                            "firstname" => $firstPage[$x + 1][1],
                            "middlename" => $firstPage[$x + 1][2],
                            "lastname" => $firstPage[$x + 1][3],
                            "suffix" => $firstPage[$x + 1][4],
                            "gender" => $firstPage[$x + 1][5],
                            "birthdate" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][6]),
                            "address" => $firstPage[$x+1][7],
                            "salary" => $firstPage[$x+1][19],
                            "p_email" => $firstPage[$x + 1][8],
                            "contact_number" => $firstPage[$x + 1][10],
                            "status" => $firstPage[$x + 1][17],
                            "hired_date" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][20]),
                            "separation_date" => $this->excel_date->excelDateToPHPDate($firstPage[$x + 3][21]),
                            "status_reason" => $firstPage[$x + 1][19],
                            "excel_hash" =>  strtolower($firstPage[$x + 1][0]. $firstPage[$x + 1][1]. $firstPage[$x + 1][2]),
                            "email"=> $firstPage[$x + 1][9],
                            "password"=> bcrypt($firstPage[$x + 1][0]. $firstPage[$x + 1][2]),
                            "company_id"=> $firstPage[$x + 1][1],
                            "contract"=> $firstPage[$x + 1][18],
                            "login_flag"=> 0,
                            "access_id"=> $firstPage[$x + 1][15],
                            "parent_id" =>$firstPage[$x + 1][16],
                            "benefits" => $benefits
                        );
                     
                }
            }
            $benefits = [];
        };
        $userInfo['auth_id'] = auth::id();
        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Uploaded Users",
            "description" => "Import User Success!",
            "meta"        => [
                "failed"        => $userInfo,
            ],
           
           
        ]);
        $result = $this->addUser($userInfo);
        return $result;

    }
    public function addUser($data = [])
    {   
        // data validation
        $error_array =  new ArrayObject();
        $error_count=0;
        $action=null;
        $user_datani=[];
        $user_information = [];
        $hierarchy = [];
        $cluster=[];
        $user_benefits=[];
        $benefits=[];
        $result = $this->access_level;
        $access = $this->genericSearch($data, $result)->get()->all();
        foreach ($data as $key => $value) { 
            $user_information['firstname']= $data[$key]['firstname'];
            $user_information['middlename']= $data[$key]['middlename']; 
            $user_information['lastname']= $data[$key]['lastname'];
            $user_information['birthdate']= $data[$key]['birthdate'];
            $user_information['gender']= $data[$key]['gender'];   
            $user_information['contact_number']= $data[$key]['contact_number'];
            $user_information['address']= $data[$key]['address'];
            $user_information['status']= $data[$key]['status'];
            $user_information['hired_date']= $data[$key]['hired_date'];
            $user_information['separation_date']= $data[$key]['separation_date'];
            $user_information['excel_hash']= $data[$key]['excel_hash'];
            $user_information['p_email']= $data[$key]['p_email'];
            $user_information['status_reason']= $data[$key]['status_reason'];
          
            $user_informations =  $this->user_infos->init($this->user_infos->pullFillable($user_information));
                if (!$user_informations->save($data)) {
                    if (strpos($user_informations->errors(), 'user_infos_excel_hash_unique') !== false) {
                        $error_array->offsetSet($key, "Full Name is already in Use. Please use another Name.");
                        $error_count++;
                        continue;
                    }else{
                        $error_array->offsetSet($key,  $user_informations->errors());
                        $error_count++;
                        continue;
                    }
                }      
            $user_id = $user_informations->id;
          
            $hierarchy['child_id']= $user_id;
            $hierarchy['parent_id']= null;
        
            $user_hierarchy= $this->access_level_hierarchy->init($this->access_level_hierarchy->pullFillable($hierarchy));
            
            $user_data['uid']= $user_id;
            $user_data['email']= $data[$key]['user'][0]['email'];
            foreach ($access as $keys => $val) {
                if ($access[$keys]['name']==$data[$key]['user'][0]['access_id']) {
                    $user_data['access_id']= $access[$keys]['id'];
                }
            }    
            $user_data['company_id']= $data[$key]['user'][0]['company_id'];
            $user_data['contract']= $data[$key]['user'][0]['contract'];
            $user_data['login_flag']= $data[$key]['user'][0]['login_flag'];
            $user_data['password'] = $data[$key]['user'][0]['password'];
            $users_data = $this->user_datum->init($this->user_datum->pullFillable($user_data));
            $status_logs['user_id']=$user_id;
            $status_logs['status']=$data[$key]['status'];
            $status_logs['type']="New Hired";
            $status_logs['hired_date']=$data[$key]['hired_date'];
            $status = $this->user_status->init($this->user_status->pullFillable($status_logs)); 
            $action="Created";  
            if (!$status->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                $error_array->offsetSet('status_save', "Saving Error on Status");
                $error_count++;       
            }
            if (!$user_hierarchy->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                $error_array->offsetSet('user_hierarchy_error', "Saving Error on User Hierarchy");
                $error_count++;  
            }   
            if (!$users_data->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);    
                if($user_info_delete){
                    $user_info_delete->forceDelete();
                }   
                if (strpos($users_data->errors(), 'users_email_unique') !== false) {
                    $error_array->offsetSet($key, "Email is already in use. Please use another valid email.");
                    $error_count++;  
                }
               
            }  
            $benefits=[];
            $ben=[];
           // $array=json_decode($data['benefits'], true );
            if($data[$key]['benefits']==[]){
                for($i=1; $i<5;$i++ ){
                    $ben['benefit_id'] = $i;
                    $ben['id_number'] = NULL;
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));   
                    array_push($benefits,$user_ben);
                    $user_ben->save();   
            }   
            }else{
                foreach($data[$key]['benefits'] as $keyss => $valuess ){
                   
                    $ben['benefit_id'] = $keyss+1;
                    $user_bene['id_number']=$valuess;
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
             }
            
           
            
        }
         return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Uploaded Users",
            "description" => "Import User Success!",
            "meta"        => [
                "failed"        => $error_array,
            ],
           
           
        ]);
    }
    
}
