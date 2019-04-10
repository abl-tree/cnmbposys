<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\Data\Models\Users;
use App\Data\Models\SelectUsers;
use App\User;
use App\Data\Models\UserReport;
use App\Data\Models\SanctionType;
use App\Data\Models\SanctionLevel;
use App\Data\Models\SanctionTypes;
use App\Data\Models\SanctionLevels;
use App\Data\Models\ReportResponse;
use App\Data\Repositories\BaseRepository;

class ReportsRepository extends BaseRepository
{

    protected 
        $user_info,
        $users,
        $select_users,
        $user,
        $user_reports,
        $sanction_type,
        $sanction_types,
        $sanction_levels,
        $report_response,
        $sanction_level;

    public function __construct(
        UserInfo $user_info,
        Users $users,
        SelectUsers $select_users,
        User $user,
        SanctionType $sanction_type,
        SanctionLevel $sanction_level,
        SanctionTypes $sanction_types,
        SanctionLevels $sanction_levels,
        ReportResponse $report_response,
        UserReport $user_reports
    ) {
        $this->user_info = $user_info;
        $this->users = $users;
        $this->select_users = $select_users;
        $this->user = $user;
        $this->user_reports = $user_reports;
        $this->sanction_level = $sanction_level;
        $this->sanction_type = $sanction_type;
        $this->sanction_levels = $sanction_levels;
        $this->sanction_types = $sanction_types;
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
        $results=[];
        foreach ($result as $key => $value) {
             if($value->reports!="[]"){        
                array_push($results,$value);
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
        
        if (!isset($data['id'])) {
            if (!isset($data['user_reports_id']) ||
                !is_numeric($data['user_reports_id']) ||
                $data['user_reports_id'] <= 0) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "User report ID is not set.",
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
        }   else{
            if (isset($data['id'])) {
                $does_exist = $this->user_reports->find($data['id']);

                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'Request Schedule does not exist.',
                    ]);
                }
            }
            if (isset($data['user_reports_id'])) {
                $user_reports_id = $this->user_reports->find($data['user_reports_id']);
    
                if (!$user_reports_id) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'user_reports_id does not exist.',
                    ]);
                }
            }
        }
           

            if (isset($data['id'])) {
                $reports = $this->user_reports->find($data['id']);
            } else{
                $reports = $this->user_reports->init($this->user_reports->pullFillable($data));
            }
            
           

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

    public function deleteReport($data = [])
    {
        $record = $this->user_reports->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Incident Report not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting Incident Report was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'schedule_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Incident Report deleted",
            "description" => "Incident Report deleted successfully.",
            "parameters"        => [
                "schedule_id" => $data['id']
            ]
        ]);

    }

    public function deleteStype($data = [])
    {
        $record = $this->sanction_type->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Sanction Type not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting Sanction Type was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'sanction_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Sanction Type deleted",
            "description" => "Sanction Type deleted successfully.",
            "parameters"        => [
                "sanction_id" => $data['id']
            ]
        ]);

    }

    public function deleteSlevel($data = [])
    {
        $record = $this->sanction_level->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Sanction Level not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting Sanction Level was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'sanction_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Sanction Level deleted",
            "description" => "Sanction Level deleted successfully.",
            "parameters"        => [
                "sanction_id" => $data['id']
            ]
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
        $results=[];
        $keys=0;
        foreach ($result as $key => $value) {
             if($value->reports!="[]"){        
                array_push($results,$value);
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
        if (!isset($data['id'])) {
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
        }else{
            if (isset($data['id'])) {
                $does_exist = $this->sanction_type->find($data['id']);
                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'Sanction Type does not exist.',
                    ]);
                }
            }
        }

        if (isset($data['id'])) {
            $sanctionType = $this->sanction_type->find($data['id']);
            $sanctionType->save();
        } else{
            $sanctionType = $this->sanction_type->init($this->sanction_type->pullFillable($data));
        }
        
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
            "title"      => "Successfully Edited a Sanction Type.",
            "parameters" => $sanctionType,
        ]);
        
    }


 public function addSanctionLevel($data = [])
    {
        // data validation
        if (!isset($data['id'])) {
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
        }else{
            if (isset($data['id'])) {
                $does_exist = $this->sanction_level->find($data['id']);
                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'Sanction Type does not exist.',
                    ]);
                }
            }
        }
        if (isset($data['id'])) {
            $sanctionLevel = $this->sanction_level->find($data['id']);
            $sanctionLevel->save();
        } else{
            $sanctionLevel = $this->sanction_level->init($this->sanction_level->pullFillable($data));
        }
        
            
            

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
            "title"      => "Successfully Edited a Sanction Level.",
            "parameters" => $sanctionLevel,
        ]);
        
    }

         public function userResponse($data = [])
    {
        // data validation
        if (!isset($data['user_response_id'])) {
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
        }else{
            if (isset($data['user_response_id'])) {
                $does_exist = $this->report_response->find($data['user_response_id']);
                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'Sanction Type does not exist.',
                    ]);
                }
            }
        }
        if (isset($data['user_response_id'])) {
            $response = $this->report_response->find($data['user_response_id']);
            $response->save();
        } else{
            $response = $this->report_response->init($this->report_response->pullFillable($data));
        }
        
            
            

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
        $meta_index = "options";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "options";
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
        $meta_index = "options";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "options";
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
        $results=[];
        $keys=0;
        $last_child=null;
        foreach ($result as $key => $value) {
              if($value->accesslevelhierarchy->parent_id==$data['id']){
                  $last_child=$value->accesslevelhierarchy->child_id;
                  array_push($results,$value);
                foreach ($result as $key => $val) {
                    $last_child2=null;
                    if($val->accesslevelhierarchy->parent_id==$last_child){
                        $keys++;
                        $count++;  
                        array_push($results,$val);
                        foreach ($result as $key => $vals) {
                            $last_child2=$val->accesslevelhierarchy->child_id;
                            if($vals->accesslevelhierarchy->parent_id==$last_child2){
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
            "title"      => "Successfully retrieved Users under this Parent",
            "description"=>"Users under this Parent",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function getSelectAllUserUnder($data = [])
    {
        $meta_index = "options";
        $parameters = [];
        $count      = 0;
         

        $count_data = $data;
        $data['relations'] = ["accesslevel","accesslevelhierarchy"];   
        $result = $this->fetchGeneric($data, $this->select_users);
        $results=[];
        $keys=0;
        $last_child=null;
        foreach ($result as $key => $value) {
              if($value->accesslevelhierarchy->parent_id==$data['id']){
                  $last_child=$value->accesslevelhierarchy->child_id;
                  array_push($results,$value);
                foreach ($result as $key => $val) {
                    $last_child2=null;
                    if($val->accesslevelhierarchy->parent_id==$last_child){
                        $keys++;
                        $count++;  
                        array_push($results,$val);
                        foreach ($result as $key => $vals) {
                            $last_child2=$val->accesslevelhierarchy->child_id;
                            if($vals->accesslevelhierarchy->parent_id==$last_child2){
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
            "title"      => "Successfully retrieved Users under this Parent",
            "description"=>"Users under this Parent",
            "meta"       => [
                $meta_index => $results,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function getSanctionTypes($data = [])
    {
        $meta_index = "options";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "options";
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
        $result = $this->fetchGeneric($data, $this->sanction_types);

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
       
        $count = $this->countData($count_data, refresh_model($this->sanction_types->getModel()));

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
     public function getSanctionLevels($data = [])
    {
        $meta_index = "options";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "options";
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
        $result = $this->fetchGeneric($data, $this->sanction_levels);

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
       
        $count = $this->countData($count_data, refresh_model($this->sanction_levels->getModel()));

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




}
