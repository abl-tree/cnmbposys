<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 06/05/2019
 * Time: 10:25 PM
 */

namespace App\Data\Repositories;

use App\Data\Repositories\BaseRepository;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\UserInfo;

class AccessLevelHierarchyRepository extends BaseRepository
{
    protected $access_level, $user_info;


    public function __construct(
        AccessLevelHierarchy $access_level,
        UserInfo $user_info
    ) {
        $this->access_level = $access_level;
        $this->user_info = $user_info;
    }

    public function defineAccessLevelHierarchy($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            // data validation
            if (!isset($data['parent_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Parent ID is not set.",
                ]);
            }

            // data validation
            if (!isset($data['child_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Child ID is not set.",
                ]);
            }

        }

        // existence check

        if (isset($data['parent_id'])) {
            if (!$this->user_info->find($data['parent_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        if (isset($data['child_id'])) {
            if (!$this->user_info->find($data['child_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        // insertion
        if (isset($data['id'])) {
            $acc_level = $this->access_level->find($data['id']);
        } else {
            $acc_level = $this->access_level->init($this->access_level->pullFillable($data));
        }

        if (!$acc_level->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $acc_level->errors(),
                ],
            ]);
        }


    }
}