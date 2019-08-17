<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\Data\Models\UsersData;
use App\Data\Models\ActionLogs;
use App\Data\Repositories\BaseRepository;

class LogsRepository extends BaseRepository
{

    protected 
        $user_info,
        $user,
        $action_logs;

    public function __construct(
        UserInfo $user_info,
        UsersData $user,
        ActionLogs $action_logs
    ) {
        $this->user_info = $user_info;
        $this->user = $user;
        $this->action_logs = $action_logs;
    } 

    public function logs($data = [])
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

            $parameters['user_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = ["user","accesslevelhierarchy"];        
        $count_data = $data;    
        $result = $this->fetchGeneric($data, $this->action_logs);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No logs are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->action_logs->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved logs",
            "description"=>"Logs",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "count"     => $count,
            //"parameters" => $data['user_id'],
            
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
        $meta_index = "metadata";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "metadata";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "user_id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['user_id'] = $data['id'];

        }

        $count_data = $data;

        $data['relations'] = ["user","accesslevelhierarchy"];       

        $result = $this->fetchGeneric($data, $this->action_logs);

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

        $count = $this->countData($count_data, refresh_model($this->action_logs->getModel()));

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



    public function search($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->action_logs;
        $data['relations'] = ["user","accesslevelhierarchy"];       

        $meta_index = "logs";
        $parameters = [
            "query" => $data['query'],
        ];

        foreach ((array) $data['target'] as $index => $column) {
            if (str_contains($column, "full_name")) {
                
                $data['target'][] = 'userinfo.firstname';
                $data['target'][] = 'userinfo.middlename';
                $data['target'][] = 'userinfo.lastname';
                unset($data['target'][$index]);
            }
        }

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No logs are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->action_logs->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched logs",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }


}
