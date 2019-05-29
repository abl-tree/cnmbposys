<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\Data\Models\UsersData;
use App\Data\Models\UserCluster;
use App\Data\Models\UpdateStatus;
use App\Data\Repositories\BaseRepository;

class UsersInfoRepository extends BaseRepository
{

    protected 
        $user_info,$user_status,$user_infos,
        $user;

    public function __construct(
        UsersData $user_info,
        UserInfo $user_infos,
        User $user,
        UpdateStatus $user_status,
        UserCluster $select_users
    ) {
        $this->user_info = $user_info;
        $this->user_infos = $user_infos;
        $this->user = $user;
        $this->user_status = $user_status;
        $this->select_users = $select_users;
    } 

    public function usersInfo($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "metadata";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

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
            "title"      => "Successfully retrieved users Information",
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

                $status = $this->user_status->init($this->user_status->pullFillable($data));
                $Users = $this->user_infos->find($data['user_id']);
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
                return $this->setResponse([
                    "code"       => 200,
                    "title"      => "Successfully ".$action." a User Status.",
                    "meta"        => [
                        "Users" => $Users,
                        "Status Log" => $status
                    ]
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
