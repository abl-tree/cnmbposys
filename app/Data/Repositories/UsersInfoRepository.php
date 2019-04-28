<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\Data\Models\UsersData;
use App\Data\Repositories\BaseRepository;

class UsersInfoRepository extends BaseRepository
{

    protected 
        $user_info,
        $user;

    public function __construct(
        UsersData $user_info,
        User $user
    ) {
        $this->user_info = $user_info;
        $this->user = $user;
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


}
