<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 12/05/2019
 * Time: 5:03 PM
 */

namespace App\Data\Repositories;

use App\Data\Repositories\BaseRepository;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Repositories\AccessLevelHierarchyRepository;
use App\Data\Models\Clusters;
use App\Data\Models\UserInfo;


class ClusterRepository extends BaseRepository
{
    protected $access_level, $access_level_repo, $clusters, $user_info;

    public function __construct(
        AccessLevelHierarchy $access_level,
        Clusters $clusters,
        UserInfo $user_info,
        AccessLevelHierarchyRepository $acc_lvl_repo
    ) {
        $this->access_level = $access_level;
        $this->clusters = $clusters;
        $this->access_level_repo = $acc_lvl_repo;
        $this->user_info = $user_info;
    }

    public function defineCluster($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            // data validation
            if (!isset($data['agent_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Agent ID is not set.",
                ]);
            }

            // data validation
            if (!isset($data['tl_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Child ID is not set.",
                ]);
            }

            if (!isset($data['om_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Child ID is not set.",
                ]);
            }

        }

        // existence check

        if (isset($data['agent_id'])) {
            if (!$this->user_info->find($data['agent_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        if (isset($data['tl_id'])) {
            if (!$this->user_info->find($data['tl_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        if (isset($data['om_id'])) {
            if (!$this->user_info->find($data['om_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        // insertion
        if (isset($data['id'])) {
            $cluster = $this->clusters->find($data['id']);
        } else {
            $cluster = $this->clusters->init($this->clusters->pullFillable($data));
        }

        if (!$cluster->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $cluster->errors(),
                ],
            ]);
        }

        $access_hierarchy_agent = $this->access_level->where('child_id', $data['agent_id'])->first();
        if ($access_hierarchy_agent) {
            $arr = [
                "id" => $access_hierarchy_agent->id,
                "parent_id" => $data['tl_id'],
                "child_id" => $data['agent_id']
            ];
            $access_level_hierarchy = $this->access_level_repo->defineAccessLevelHierarchy($arr);
            if ($access_level_hierarchy) {
                $access_hierarchy = $this->access_level->where('child_id', $data['tl_id'])->first();
                if ($access_hierarchy) {
                    $arr = [
                        "id" => $access_hierarchy->id,
                        "parent_id" => $data['om_id'],
                        "child_id" => $data['tl_id']
                    ];
                    $this->access_level_repo->defineAccessLevelHierarchy($arr);
                }
                else{
                    $arr = [
                        "parent_id" => $data['om_id'],
                        "child_id" => $data['tl_id']
                    ];
                    $this->access_level_repo->defineAccessLevelHierarchy($arr);
                }
            }
        }
        else {
            $arr = [
                "parent_id" => $data['tl_id'],
                "child_id" => $data['agent_id']
            ];
            $access_level_hierarchy = $this->access_level_repo->defineAccessLevelHierarchy($arr);
            if ($access_level_hierarchy) {
                $access_hierarchy = $this->access_level->where('child_id', $data['tl_id'])->first();
                if ($access_hierarchy) {
                    $arr = [
                        "id" => $access_hierarchy->id,
                        "parent_id" => $data['om_id'],
                        "child_id" => $data['tl_id']
                    ];
                    $this->access_level_repo->defineAccessLevelHierarchy($arr);
                }
                else{
                    $arr = [
                        "parent_id" => $data['om_id'],
                        "child_id" => $data['tl_id']
                    ];
                    $this->access_level_repo->defineAccessLevelHierarchy($arr);
                }
            }
        }




        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a cluster.",
            "parameters" => $cluster,
        ]);



    }
}