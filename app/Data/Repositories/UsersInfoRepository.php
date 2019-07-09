<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\Data\Models\UsersData;
use App\Data\Models\Users;
use App\Data\Models\UserCluster;
use App\Data\Models\UpdateStatus;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\UserBenefit;
use App\Data\Models\BenefitUpdate;
use App\Data\Models\HierarchyUpdate;
use App\Data\Repositories\BaseRepository;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\Storage;

class UsersInfoRepository extends BaseRepository
{

    protected 
        $user_info,$user_datum,$user_status,$user_benefits,$user_infos,
        $user,$access_level_hierarchy,$benefit_update,$hierarchy_update,$no_sort;

    public function __construct(
        UsersData $user_info,
        BenefitUpdate $benefit_update,
        HierarchyUpdate $hierarchy_update,
        UserInfo $user_infos,
        User $user,
        Users $user_datum,
        UpdateStatus $user_status,
        UserCluster $select_users,
        UserBenefit $user_benefits,
        AccessLevelHierarchy $access_level_hierarchy
    ) {
        $this->user_info = $user_info;
        $this->user_infos = $user_infos;
        $this->benefit_update = $benefit_update;
        $this->hierarchy_update = $hierarchy_update;
        $this->user = $user;
        $this->user_datum = $user_datum;
        $this->user_status = $user_status;
        $this->select_users = $select_users;
        $this->user_benefits = $user_benefits;
        $this->access_level_hierarchy = $access_level_hierarchy;
        
    } 

    public function usersInfo($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "metadata";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

        }

        if (isset($data['target'])) {
            $result = $this->user_info;
            $data['relations'] = ["user_info","accesslevel","benefits"];     
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'user_info.firstname';
                    $data['target'][] = 'user_info.middlename';
                    $data['target'][] = 'user_info.lastname';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "gender")) {
                    $data['target'][] = 'user_info.gender';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "position")) {
                    $data['target'][] = 'accesslevel.name';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "p_email")) {
                    $data['target'][] = 'user_info.p_email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "status")) {
                    $data['target'][] = 'user_info.status';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "type")) {
                    $data['target'][] = 'user_info.type';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "address")) {
                    $data['target'][] = 'user_info.address';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "birthdate")) {
                    $data['target'][] = 'user_info.birthdate';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "hired_date")) {
                $count=0;
                $array=json_decode($data['query'], true );
                $general=[];
                $start    = new DateTime($array[0]);
                $end      = (new DateTime($array[1]))->modify('+1 day');
                $interval = new DateInterval('P1D');
                $period   = new DatePeriod($start, $interval, $end);
                foreach ($period as $dt) {
                   // array_push($date,$dt->format("Y-m-d"));
                    $data['query'] = $dt->format("Y-m-d");
                    $data['target'][] = 'user_info.hired_date';
                    unset($data['target'][$index]);

                   // $count_data = $data;
                    $results = $this->genericSearch($data, $result)->get()->all();
                    if($results){
                        array_push($general,$results);
                         $count+=1;
                    }
                   
                }  
                $new = [];
                while($item = array_shift($general)){
                    array_push($new, ...$item);
                }           
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }
            
                    $count_data['search'] = true;
                   // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
            
                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
                if (str_contains($column, "separation_date")) {
                    $count=0;
                    $array=json_decode($data['query'], true );
                    $general=[];
                    $start    = new DateTime($array[0]);
                    $end      = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period   = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                       // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'user_info.separation_date';
                        unset($data['target'][$index]);
    
                       // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if($results){
                            array_push($general,$results);
                             $count+=1;
                        }
                       
                    }  
                    $new = [];
                    while($item = array_shift($general)){
                        array_push($new, ...$item);
                    }           
                        if ($result == null) {
                            return $this->setResponse([
                                'code' => 404,
                                'title' => "No user are found",
                                "meta" => [
                                    $meta_index => $new,
                                ],
                                "parameters" => $parameters,
                            ]);
                        }
                
                        $count_data['search'] = true;
                       // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
                
                        return $this->setResponse([
                            "code" => 200,
                            "title" => "Successfully searched Users",
                            "meta" => [
                                $meta_index => $new,
                                "count" => $count,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }
            }
            $count_data = $data;
            $result = $this->genericSearch($data, $result)->get()->all();
    
            if ($result == null) {
                return $this->setResponse([
                    'code' => 404,
                    'title' => "No user are found",
                    "meta" => [
                        $meta_index => $result,
                    ],
                    "parameters" => $parameters,
                ]);
            }
    
            $count_data['search'] = true;
            $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
    
            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully searched Users",
                "meta" => [
                    $meta_index => $result,
                    "count" => $count,
                ],
                "parameters" => $parameters,
            ]);   
        }
        $count_data = $data;
        $data['relations'] = ["user_info", "accesslevel", "benefits"];
        $count_data = $data;    
        $result = $this->fetchGeneric($data, $this->user_info);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Users are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved users Informations",
            "description"=>"UserInfo",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "count"     => $count,
            "parameters" => $parameters,
            
        ]);
    }

    public function logsInputCheck($data = [])
    {
        // data validation
        

            if (!isset($data['user_id']) ||
                !is_numeric($data['user_id']) ||
                $data['user_id'] <= 0) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User ID is not set.",
                ]);
            }

            if (!isset($data['action'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "action ID is not set.",
                ]);
            }

            if (!isset($data['affected_data'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "data affected is not set.",
                ]);
            }

       
            $action_logs = $this->action_logs->init($this->action_logs->pullFillable($data));
            $action_logs->save($data);

        if (!$action_logs->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $action_logs->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an agent schedule.",
            "parameters" => $action_logs,
        ]);
        
    }

    public function addUser($data = [])
    {
        // data validation
        $action=null;
        $user_datani=[];
        $user_information = [];
        $hierarchy = [];
        $cluster=[];
        $user_benefits=[];
        $benefits=[];
        if (!isset($data['id'])) {
            if (!isset($data['firstname'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "First Name is not set.",
                ]);
            }else{
                $user_information['firstname']= $data['firstname'];
            }
            if (!isset($data['middlename'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "middlename is not set.",
                ]);
            }else{
                $user_information['middlename']= $data['middlename'];
            }
            if (!isset($data['lastname'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "lastname is not set.",
                ]);
            }else{
                $user_information['lastname']= $data['lastname'];
            }
            if (!isset($data['birthdate'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "birthdate is not set.",
                ]);
            }else{
                $user_information['birthdate']= $data['birthdate'];
            }
            if (!isset($data['gender'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "gender is not set.",
                ]);
            }else{
                $user_information['gender']= $data['gender'];
            }    
            if (!isset($data['email'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Email is not set.",
                ]);
            }if (!isset($data['access_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "access_id is not set.",
                ]);
            }

            if (isset($data['contact_number'])) {
                $user_information['contact_number']= $data['contact_number'];
            }
            if (isset($data['address'])) {
                $user_information['address']= $data['address'];
            }
            if (isset($data['salary_rate'])) {
                $user_information['salary_rate']= $data['salary_rate'];
            }
            if (isset($data['status'])) {
                $user_information['status']= $data['status'];
            }
            if (isset($data['type'])) {
                $user_information['type']= $data['type'];
            }
            if (isset($data['hired_date'])) {
                $user_information['hired_date']= $data['hired_date'];
            }
            if (isset($data['separation_date'])) {
                $user_information['separation_date']= $data['separation_date'];
            }
            if (isset($data['excel_hash'])) {
                $user_information['excel_hash']= $data['excel_hash'];
            }
            if (isset($data['p_email'])) {
                $user_information['p_email']= $data['p_email'];
            }
            if (isset($data['status_reason'])) {
                $user_information['status_reason']= $data['status_reason'];
            }
            if (isset($data['imageName'])) {
                define('UPLOAD_DIR', 'storage/images/');
                $file =  request()->image->move(UPLOAD_DIR,$data['imageName']);
                $url= asset($file);
                $user_information['image_url']= $url;
            }
            $user_informations =  $this->user_infos->init($this->user_infos->pullFillable($user_information));
            $user_informations->save();
            $user_id= $user_informations->id;
            $hierarchy['child_id']= $user_id;
            if (isset($data['parent_id'])) {
                $hierarchy['parent_id']= $data['parent_id'];
            }
           $user_hierarchy= $this->access_level_hierarchy->init($this->access_level_hierarchy->pullFillable($hierarchy));
           $user_hierarchy->save();
           $user_data['uid']= $user_id;
            if (isset($data['email'])) {
                $user_data['email']= $data['email'];
            }
            if (isset($data['access_id'])) {
                $user_data['access_id']= $data['access_id'];
            }
            if (isset($data['company_id'])) {
                $user_data['company_id']= $data['company_id'];
            }
            if (isset($data['contract'])) {
                $user_data['contract']= $data['contract'];
            }
            $user_data['password'] = bcrypt(strtolower($data['firstname'].$data['lastname']));
            $users_data = $this->user_datum->init($this->user_datum->pullFillable($user_data));
            $users_data;

            $benefits=[];
            $ben=[];
            $array=json_decode($data['benefits'], true );
            foreach($array as $key => $value ){
                    $ben['benefit_id'] = $key+1;
                    $ben['id_number'] = $value;
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));   
                    array_push($benefits,$user_ben);
                    $user_ben->save();   
            }  
            if (isset($data['status'])) {
            $status_logs['user_id']=$user_id;
            $status_logs['status']=$data['status'];
            }
            if (isset($data['type'])) {
                $status_logs['type']=$data['type'];
            }
            if (isset($data['status_reason'])) {
            $status_logs['reason']=$data['status_reason'];
            }
            if (isset($data['hired_date'])) {
            $status_logs['hired_date']=$data['hired_date'];
            }
            if (isset($data['separation_date'])) {
            $status_logs['separation_date']=$data['separation_date'];
            }
            $status = $this->user_status->init($this->user_status->pullFillable($status_logs)); 
            $status->save(); 
            $action="Created";        
        }else{
            if (isset($data['id'])) {
                $user_information = $this->user_infos->find($data['id']);
                if($user_information){
                if (isset($data['firstname'])) {
                    $user_information['firstname']= $data['firstname'];
                }
                if (isset($data['lastname'])) {
                    $user_information['lastname']= $data['lastname'];
                }
                if (isset($data['middlename'])) {
                    $user_information['middlename']= $data['middlename'];
                }
                if (isset($data['suffix'])) {
                    $user_information['suffix']= $data['suffix'];
                }
                if (isset($data['address'])) {
                    $user_information['address']= $data['address'];
                }
                if (isset($data['contact_number'])) {
                    $user_information['contact_number']= $data['contact_number'];
                }
                if (isset($data['salary_rate'])) {
                    $user_information['salary_rate']= $data['salary_rate'];
                }
                if (isset($data['status'])) {
                    $user_information['status']= $data['status'];
                }   
                if (isset($data['type'])) {
                    $user_information['type']= $data['type'];
                }   
                if (isset($data['hired_date'])) {
                    $user_information['hired_date']= $data['hired_date'];
                }   
                if (isset($data['separation_date'])) {
                    $user_information['separation_date']= $data['separation_date'];
                }  
                if (isset($data['birthdate'])) {
                    $user_information['birthdate']= $data['birthdate'];
                }    
                if (isset($data['p_email'])) {
                    $user_information['p_email']= $data['p_email'];
                }   
                if (isset($data['status_reason'])) {
                    $user_information['status_reason']= $data['status_reason'];
                }
                if (isset($data['gender'])) {
                    $user_information['gender']= $data['gender'];
                }        
                if (isset($data['imageName'])) {
                    if($user_information->image_url==null){
                        define('UPLOAD_DIR', 'storage/images/');
                        $file =  request()->image->move(UPLOAD_DIR,$data['imageName']);
                        $url= asset($file);
                        $user_information['image_url'] = $url;
                    }else{
                        $url = $user_information->image_url; 
                        $file_name = basename($url);
                        Storage::delete('images/'.$file_name);
                        define('UPLOAD_DIR', 'storage/images/');
                        $file =  request()->image->move(UPLOAD_DIR,$data['imageName']);
                        $url= asset($file);
                        $user_information['image_url'] = $url;
                    }
                   
                }
                if(isset($data['benefits'])){
                
                $ben=[];
                $data['user_info_id']=$data['id'];
                if (isset($data['id']) &&
                is_numeric($data['id'])) {
    
                $meta_index     = "metadata";
                $data['single'] = false;
                $data['where']  = [
                    [
                        "target"   => "user_info_id",
                        "operator" => "=",
                        "value"    => $data['id'],
                    ],
                ];
    
                $parameters['id'] = $data['id'];
    
            }
                $user_ben =$this->fetchGeneric($data, $this->user_benefits);
                $array=json_decode($data['benefits'], true );
                foreach($array as $key => $value ){
                        $user_bene = $this->benefit_update->find($user_ben[$key]->id_number);
                        $user_bene['id_number'] = $value;
                        array_push($benefits,$user_bene);
                        $user_bene->save();   
                }  
            }
                    $user_data =  $this->user_datum->find($data['id']);
                    if (isset($data['email'])) {
                        $user_data['email']= $data['email'];
                    }
                    if (isset($data['access_id'])) {
                        $user_data['access_id']= $data['access_id'];
                    }
                    if (isset($data['company_id'])) {
                        $user_data['company_id']= $data['company_id'];
                    }
                    if (isset($data['contract'])) {
                        $user_data['contract']= $data['contract'];
                    }
                   $hierarchy =  $this->hierarchy_update->find($data['id']);
                    if (isset($data['parent_id'])) {
                        $hierarchy['parent_id']= $data['parent_id'];
                    }
                $user_information->save();
                $user_data->save();
                $hierarchy->save();
                $action="Updated";
                return $this->setResponse([
                    "code"       => 200,
                    "title"      => "Successfully ".$action." a User.",
                    "meta"        => [
                        "user_information" => $user_information,
                        "user" => $user_data,
                        "benefits" => $benefits,
                        "hierarchy"=>$hierarchy
                    ]
                ]);
                }else{
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'User Not Found.',
                    ]);
               
                }
            }
        }
            // if (isset($data['id'])) {
            //     $Users = $this->users->find($data['id']);
            //     $action="Updated";
            // } else{
            //     $data['password'] = bcrypt('password');
            //     $Users = $this->users->init($this->users->pullFillable($data));
            //     $user_information =  $this->user_infos->init($this->user_infos->pullFillable($data));
            //     $action="Added";
            // }
            
           

        if (!$users_data->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $users_data->errors(),
                ],
            ]);
        }
        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully ".$action." a User.",
            "meta"        => [
                "user_information" => $user_informations,
                "user" => $users_data,
                "benefits" => $benefits
            ]
        ]);
        
    }

    public function updateStatus($data = [])
    {
        // data validation
        $action=null;
       
            if (!isset($data['status'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "status is not set.",
                ]);
            }
            if (!isset($data['user_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "user id is not set.",
                ]);
            }
            if (!isset($data['reason'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "reason is not set.",
                ]);
            }   
            if (!isset($data['type'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "type is not set.",
                ]);
            }   

                $status = $this->user_status->init($this->user_status->pullFillable($data));
                $Users = $this->user_infos->find($data['user_id']);
                $Users->status=$data['status'];
                $Users->status_reason=$data['reason'];
                $Users->type=$data['type'];
                if(isset($data['hired_date'])){
                    $Users->hired_date=$data['hired_date'];
                }
                if(isset($data['separation_date'])){
                    $Users->separation_date=$data['separation_date'];
                }
                $action="Updated";
                if (!$Users->save($data)) {
                    return $this->setResponse([
                        "code"        => 500,
                        "title"       => "Data Validation Error on User.",
                        "description" => "An error was detected on one of the inputted data.",
                        "meta"        => [
                            "errors" => $Users->errors(),
                        ],
                    ]);
                }
                if (!$status->save($data)) {
                    return $this->setResponse([
                        "code"        => 500,
                        "title"       => "Data Validation Error.",
                        "description" => "An error was detected on one of the inputted data.",
                        "meta"        => [
                            "errors" => $status->errors(),
                        ],
                    ]);
                }
                return $this->setResponse([
                    "code"       => 200,
                    "title"      => "Successfully ".$action." a User Status.",
                    "meta"        => [
                        "Users" => $Users,
                        "Status Log" => $status
                    ]
                ]);
                    
        
        
    }

    public function bulkUpdateStatus($data = [])
    {
        // data validation
        $action=null;
        $array=json_decode($data['user_id'], true );
       $all_users=[];
        foreach ($array as $key => $value) {
            $data['user_id']=$value;          
            if (!isset($data['status'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "status is not set.",
                ]);
            }
            if (!isset($data['user_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "user id is not set.",
                ]);
            }
            if (!isset($data['reason'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "reason is not set.",
                ]);
            }   

                $status = $this->user_status->init($this->user_status->pullFillable($data));
                $Users = $this->user_infos->find($value);
                $Users->status=$data['status'];
                $Users->status_reason=$data['reason'];
                if(isset($data['hired_date'])){
                    $Users->hired_date=$data['hired_date'];
                }
                if(isset($data['separation_date'])){
                    $Users->separation_date=$data['separation_date'];
                }
                $action="Updated";
                if (!$Users->save($data)) {
                    return $this->setResponse([
                        "code"        => 500,
                        "title"       => "Data Validation Error on User.",
                        "description" => "An error was detected on one of the inputted data.",
                        "meta"        => [
                            "errors" => $Users->errors(),
                        ],
                    ]);
                }
                if (!$status->save($data)) {
                    return $this->setResponse([
                        "code"        => 500,
                        "title"       => "Data Validation Error.",
                        "description" => "An error was detected on one of the inputted data.",
                        "meta"        => [
                            "errors" => $status->errors(),
                        ],
                    ]);
                }
                array_push($all_users,$Users);
            }
                return $this->setResponse([
                    "code"       => 200,
                    "title"      => "Successfully ".$action." a Users Status.",
                    "meta"        => [
                        "Users" => $all_users
                    ]
                ]);
                    
        
        
    }

    public function search($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->user_info;

        $meta_index = "users";
        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ["user_info","accesslevel","benefits"];     

        // $data['where'] = [
        //     [
        //         "target" => "access_id",
        //         "operator" => "=",
        //         "value" => '17',
        //     ],
        // ];

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'user_info.firstname';
                    $data['target'][] = 'user_info.middlename';
                    $data['target'][] = 'user_info.lastname';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "gender")) {
                    $data['target'][] = 'user_info.gender';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "position")) {
                    $data['target'][] = 'accesslevel.name';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "p_email")) {
                    $data['target'][] = 'user_info.p_email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "status")) {
                    $data['target'][] = 'user_info.status';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "type")) {
                    $data['target'][] = 'user_info.type';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "address")) {
                    $data['target'][] = 'user_info.address';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "birthdate")) {
                    $data['target'][] = 'user_info.birthdate';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "hired_date")) {
                $count=0;
                $array=json_decode($data['query'], true );
                $general=[];
                $start    = new DateTime($array[0]);
                $end      = (new DateTime($array[1]))->modify('+1 day');
                $interval = new DateInterval('P1D');
                $period   = new DatePeriod($start, $interval, $end);
                foreach ($period as $dt) {
                   // array_push($date,$dt->format("Y-m-d"));
                    $data['query'] = $dt->format("Y-m-d");
                    $data['target'][] = 'user_info.hired_date';
                    unset($data['target'][$index]);

                   // $count_data = $data;
                    $results = $this->genericSearch($data, $result)->get()->all();
                    if($results){
                        array_push($general,$results);
                         $count+=1;
                    }
                   
                }  
                $new = [];
                while($item = array_shift($general)){
                    array_push($new, ...$item);
                }           
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }
            
                    $count_data['search'] = true;
                   // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
            
                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
                if (str_contains($column, "separation_date")) {
                    $count=0;
                    $array=json_decode($data['query'], true );
                    $general=[];
                    $start    = new DateTime($array[0]);
                    $end      = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period   = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                       // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'user_info.separation_date';
                        unset($data['target'][$index]);
    
                       // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if($results){
                            array_push($general,$results);
                             $count+=1;
                        }
                       
                    }  
                    $new = [];
                    while($item = array_shift($general)){
                        array_push($new, ...$item);
                    }           
                        if ($result == null) {
                            return $this->setResponse([
                                'code' => 404,
                                'title' => "No user are found",
                                "meta" => [
                                    $meta_index => $new,
                                ],
                                "parameters" => $parameters,
                            ]);
                        }
                
                        $count_data['search'] = true;
                       // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
                
                        return $this->setResponse([
                            "code" => 200,
                            "title" => "Successfully searched Users",
                            "meta" => [
                                $meta_index => $new,
                                "count" => $count,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }
            }
        }

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No user are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched Users",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }


      public function fetchUserLog($data = [])
    {
        $meta_index = "User";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "User";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['user_id'] = $data['id'];

        }

        $count_data = $data;

         $data['relations'] = ["user_info","user_logs","accesslevel"];     

        $result = $this->fetchGeneric($data, $this->user);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No agent logs are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved agent logs",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }
    public function getCluster($data = [])
    {
        $meta_index = "options";
        $parameters = [];
        $count      = 0;
         

        $count_data = $data;
        $data['relations'] = ["accesslevel","accesslevelhierarchy"];   
        $result = $this->fetchGeneric($data, $this->select_users);
        $results=[];
        $keys=0;
        $parent=null;
        foreach ($result as $key => $value) {
              if($value->accesslevelhierarchy->child_id==$data['id']){
                  $parent=$value->accesslevelhierarchy->parent_id;
                  array_push($results,$value);     
                foreach ($result as $key => $val) {
                    $last_child2=null;
                    if($val->accesslevelhierarchy->child_id==$parent){
                        $keys++;
                        $count++;  
                        array_push($results,$val);
                       
                        foreach ($result as $key => $vals) {
                            if($vals->accesslevelhierarchy->parent_id==$parent&&$vals->accesslevelhierarchy->child_id!=$data['id']){
                                $keys++;
                                $count++;  
                                array_push($results,$vals);                         
                        } 
                            $last_child2=$val->accesslevelhierarchy->parent_id;
                            if($vals->accesslevelhierarchy->child_id==$last_child2){
                                $keys++;
                                $count++;  
                                array_push($results,$vals);
                                
                            }
                          
        
                        } 
                        
                        
                    }

                } 
                
                $keys++;
                $count++;  
            }
            
         } 

        if (!$results) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No users found",
                "meta"       => [
                    $meta_index => $results,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        // $count = $this->countData($count_data, refresh_model($this->users->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Users Cluster",
            "description"=>"Cluster",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }



}
