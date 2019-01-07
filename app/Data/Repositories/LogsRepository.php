<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\ActionLogs;
use App\Data\Repositories\BaseRepository;

class LogsRepository extends BaseRepository
{

    protected 
        $user_info,
        $user;
        $action_logs;

    public function __construct(
        UserInfo $user_info,
        User $user
        ActionLogs $action_logs
    ) {
        $this->user_info = $user_info;
        $this->user = $user;
        $this->action_logs = $action_logs;
    }

    public function logs($data = [])
    {
        $meta_index = "action logs";
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

        $data['relations'] = 'info';

        $result = $this->fetchGeneric($data, $this->user);

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
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
