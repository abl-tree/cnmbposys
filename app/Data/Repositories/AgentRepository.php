<?php
/**
 * Created by PhpStorm.
 * User: Janrey
 * Date: 30/10/2018
 * Time: 2:12 PM
 */

namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\User;
use App\Data\Repositories\BaseRepository;

class AgentRepository extends BaseRepository
{

    protected 
        $user_info,
        $user;

    public function __construct(
        UserInfo $user_info,
        User $user
    ) {
        $this->user_info = $user_info;
        $this->user = $user;
    }

    public function fetchAgent($data = [])
    {
        $meta_index = "agents";
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

        }

        $data['where']  = [
            [
                "target"   => "access_id",
                "operator" => "=",
                "value"    => '17',
            ],
        ];

        $count_data = $data;

        $data['relations'] = 'info';

        $result = $this->fetchGeneric($data, $this->user);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No agents are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved agents",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
