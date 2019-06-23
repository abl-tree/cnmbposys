<?php

namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\Data\Repositories\BaseRepository;
use App\User;

class AgentRepository extends BaseRepository
{

    protected $user_info,
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
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "agent";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
                [
                    "target" => "access_id",
                    "operator" => "=",
                    "value" => '17',
                ],
            ];

            $parameters['agent_id'] = $data['id'];

        } else {

            $data['where'] = [
                [
                    "target" => "access_id",
                    "operator" => "=",
                    "value" => '17',
                ],
            ];
        }

        $count_data = $data;

        $data['relations'] = 'info';

        $result = $this->fetchGeneric($data, $this->user);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agents are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agents",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchAgent($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->user;

        $meta_index = "agents";
        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ['info'];

        $data['where'] = [
            [
                "target" => "access_id",
                "operator" => "=",
                "value" => '17',
            ],
        ];

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'info.firstname';
                    $data['target'][] = 'info.middlename';
                    $data['target'][] = 'info.lastname';
                    unset($data['target'][$index]);
                }
            }
        }

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agents are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched agents",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
