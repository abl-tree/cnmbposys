<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\Data\Models\Users;
use App\User;
use App\Data\Models\UserReport;
use App\Data\Models\SanctionType;
use App\Data\Models\SanctionLevel;
use App\Data\Models\ReportResponse;
use App\Data\Repositories\BaseRepository;

class ReportsRepository extends BaseRepository
{

    protected 
        $user_info,
        $users,
        $user,
        $user_reports,
        $sanction_type,
        $report_response,
        $sanction_level;

    public function __construct(
        UserInfo $user_info,
        Users $users,
        User $user,
        SanctionType $sanction_type,
        SanctionLevel $sanction_level,
        ReportResponse $report_response,
        UserReport $user_reports
    ) {
        $this->user_info = $user_info;
        $this->users = $users;
        $this->user = $user;
        $this->user_reports = $user_reports;
        $this->sanction_level = $sanction_level;
        $this->sanction_type = $sanction_type;
        $this->report_response = $report_response;
    } 

    public function getAllReports($data = [])
    {
        $meta_index = "all_reports";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "all_reports";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['user_reports_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = ["reports"];   
        $result = $this->fetchGeneric($data, $this->user_info);
        $results=(object)[];
        foreach ($result as $key => $value) {
            $keys=0;
             if($value->reports!="[]"){        
                $results->$keys = $value;
                $keys++;
             }
         } 

        if (!$results) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Reports are found",
                "meta"       => [
                    $meta_index => $results,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->user_reports->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Users with Reports",
            "description"=>"Users With Incident Reports",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function ReportsInputCheck($data = [])
    {
        // data validation
        

            if (!isset($data['user_reports_id']) ||
                !is_numeric($data['user_reports_id']) ||
                $data['user_reports_id'] <= 0) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User ID is not set.",
                ]);
            }

            if (!isset($data['filed_by'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "filed_by ID is not set.",
                ]);
            }

            if (!isset($data['sanction_type_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "saction type is not set.",
                ]);
            }
            if (!isset($data['sanction_level_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "saction level is not set.",
                ]);
            }

       
            $reports = $this->user_reports->init($this->user_reports->pullFillable($data));
            $reports->save($data);

        if (!$reports->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $reports->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an agent schedule.",
            "parameters" => $reports,
        ]);
        
    }

      public function fetchUserReport($data = [])
    {
       $meta_index = "reports_data";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "reports_data";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['user_reports_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = ["reports"];   
        $result = $this->fetchGeneric($data, $this->user_info);
        $results=(object)[];
        $keys=0;
        foreach ($result as $key => $value) {
             if($value->reports!="[]"){        
                $results->$keys = $value;
                $keys++;
             }
         } 

        if (!$results) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Reports are found",
                "meta"       => [
                    $meta_index => $results,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->user_reports->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Users with Reports",
            "description"=>"Users With Incident Reports",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }

     public function addSanctionType($data = [])
    {
        // data validation

            if (!isset($data['type_number'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "type_number is not set.",
                ]);
            }

            if (!isset($data['type_description'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "type description is not set.",
                ]);
            }

       
            $sanctionType = $this->sanction_type->init($this->sanction_type->pullFillable($data));
            $sanctionType->save($data);

        if (!$sanctionType->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $sanctionType->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an Sanction Type.",
            "parameters" => $sanctionType,
        ]);
        
    }


 public function addSanctionLevel($data = [])
    {
        // data validation

            if (!isset($data['level_number'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Level Number is not set.",
                ]);
            }

            if (!isset($data['level_description'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Level Description is not set.",
                ]);
            }
       
            $sanctionLevel = $this->sanction_level->init($this->sanction_level->pullFillable($data));
            $sanctionLevel->save($data);

        if (!$sanctionLevel->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $sanctionLevel->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Added a Sanction Level.",
            "parameters" => $sanctionLevel,
        ]);
        
    }

         public function userResponse($data = [])
    {
        // data validation

            if (!isset($data['user_response_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "report id is not set.",
                ]);
            }

            if (!isset($data['commitment'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Commitment is not set.",
                ]);
            }
             
       
            $response = $this->report_response->init($this->report_response->pullFillable($data));
            $response->save($data);

        if (!$response->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $response->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an Response.",
            "parameters" => $response,
        ]);
        
    }


    public function getSanctionType($data = [])
    {
        $meta_index = "sanction_types";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "sanction_types";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['sanction_type_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = [];   
        $result = $this->fetchGeneric($data, $this->sanction_type);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Sanction Types are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->sanction_type->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Sanction Type List",
            "description"=>"Sanction Type",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }
     public function getSanctionLevel($data = [])
    {
        $meta_index = "santion_levels";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "santion_levels";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['sanction_level_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = [];   
        $result = $this->fetchGeneric($data, $this->sanction_level);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Sanction Levels are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->sanction_level->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Sanction Level List",
            "description"=>"Sanction Level",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }

     public function getAllUser($data = [])
    {
        $meta_index = "all_users";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "all_users";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "uid",
                    "operator" => "!=",
                    "value"    => "3",
                ],
            ];

            $parameters['sanction_type_id'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = ["accesslevel"];   
        $data['where']  = [
            [
                "target"   => "email",
                "operator" => "!=",
                "value"    => "dev.team@cnmsolutions.net",
            ],
        ];

        $result = $this->fetchGeneric($data, $this->users);
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
       
        $count = $this->countData($count_data, refresh_model($this->users->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Sanction Type List",
            "description"=>"Sanction Type",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function userFiledIR($data = [])
    {
       $meta_index = "meta_data";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "meta_data";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "filed_by",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['filed_by'] = $data['id'];

        }
        $count_data = $data;
        $data['relations'] = ['filedby','agentResponse'];   
        $result = $this->fetchGeneric($data, $this->user_reports);
        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No IR are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->user_reports->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Filed IR by this User",
            "description"=>"Filed Incident Reports",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function getAllUserUnder($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count      = 0;
         

        $count_data = $data;
        $data['relations'] = ["accesslevel","accesslevelhierarchy"];   
        $result = $this->fetchGeneric($data, $this->users);
        $results=(object)[];
        $keys=0;
        $last_child=null;
        foreach ($result as $key => $value) {
              if($value->accesslevelhierarchy->parent_id==$data['id']){
                  $last_child=$value->accesslevelhierarchy->child_id;
                  $results->$keys = $value;
                foreach ($result as $key => $val) {
                    $last_child2=null;
                    if($val->accesslevelhierarchy->parent_id==$last_child){
                        $keys++;
                        $count++;  
                        $results->$keys = $val;
                        foreach ($result as $key => $vals) {
                            $last_child2=$val->accesslevelhierarchy->child_id;
                            if($vals->accesslevelhierarchy->parent_id==$last_child2){
                                $keys++;
                                $count++;  
                                $results->$keys = $vals;
                                
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
            "title"      => "Successfully retrieved Users under this Parent",
            "description"=>"Users under this Parent",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }




}
