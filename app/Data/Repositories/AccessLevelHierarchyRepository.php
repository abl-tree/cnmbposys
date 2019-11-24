<?php

namespace App\Data\Repositories;

use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\UserInfo;
use App\Data\Models\AccessLevel;
use App\Data\Repositories\BaseRepository;

class AccessLevelHierarchyRepository extends BaseRepository
{
    protected $access_level, $user_info,$access;

    public function __construct(
        AccessLevelHierarchy $access_level,
        AccessLevel $access,
        UserInfo $user_info
    ) {
        $this->access_level = $access_level;
        $this->access = $access;
        $this->user_info = $user_info;
    }

    public function defineAccessLevelHierarchy($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            // data validation
            if (!isset($data['parent_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Parent ID is not set.",
                ]);
            }

            // data validation
            if (!isset($data['child_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Child ID is not set.",
                ]);
            }

        }

        // existence check

        if (isset($data['parent_id'])) {
            if (!$this->user_info->find($data['parent_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User is not available.",
                ]);
            }
        }

        if (isset($data['child_id'])) {
            if (!$this->user_info->find($data['child_id'])) {
                return $this->setResponse([
                    'code' => 500,
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

        if (!$acc_level) {
            return $this->setResponse([
                'code' => 404,
                'title' => "Access level not found.",
            ]);
        }

        if (!$acc_level->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $acc_level->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an access level.",
            "parameters" => $acc_level,
        ]);

    }

    public function accessLevel($data = [])
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
       // $data['relations'] = ["user_info", "accesslevel", "benefits"];
        $count_data = $data;    
        $result = $this->fetchGeneric($data, $this->access);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No List are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->access->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Access Level Information",
            "description"=>"UserInfo",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "count"     => $count,
            "parameters" => $parameters,
            
        ]);
    }

    public function addPosition($data = [])
    {
        // data validation
             
            if (!isset($data['code'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "code is not set.",
                ]);
            }
            if (!isset($data['name'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "name  is not set.",
                ]);
            }
            if (!isset($data['parent'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "parent id  is not set.",
                ]);
            }
            $data['flag']=1;
            $position = $this->access->init($this->access->pullFillable($data));
        if (!$position->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $position->errors(),
                ],
            ]);
        }
        return $this->setResponse([
            "code"       => 200,
            "title"      => "Position Added Successfully",
            "description" => "Access Level",
            "meta"       => [
                "metadata" => $position,
            ],
        ]);
        
    }

    public function updatePosition($data = [])
    {
        $positionData = $this->access->find($data['id']);
        if($positionData==null){
            return $this->setResponse([
                'code'  => 500,
                'title' => "Access Level not found.",
            ]);
        }
        if($positionData->flag == null){
            return $this->setResponse([
                "code"       => 422,
                "title"      => "Action not processed, Not allowed to delete this access.",
            ]);
        }
        
        

        $positionData->save($data);
        if (!$positionData->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $positionData->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully updated a Position.",
            "meta"        => [
                "status" => $positionData,
            ]
        ]);
            
        
    }


    public function deletePosition($data = [])
    {
        $positionData = $this->access->find($data['id']);
        if($positionData==null){
            return $this->setResponse([
                'code'  => 500,
                'title' => "Access Level not found.",
            ]);
        }
        if($positionData->flag == null){
            return $this->setResponse([
                "code"       => 422,
                "title"      => "Action can't be processed. Position Can't be Deleted ",
            ]);
        }
        
        if (!$positionData->delete()) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $positionData->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully deleted a Coach.",
            "meta"        => [
                "status" => $positionData,
            ]
        ]);
            
        
    }

}
