<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
use App\Data\Models\Users;
use App\Data\Models\UsersData;
use App\Data\Models\SelectUsers;
use App\User;
use App\Data\Repositories\LogsRepository;
use App\Data\Models\IncidentReport;
use App\Data\Models\UserReport;
use App\Data\Models\SanctionType;
use App\Data\Models\SanctionLevel;
use App\Data\Models\SanctionTypes;
use App\Data\Models\SanctionLevels;
use App\Data\Models\ReportResponse;
use App\Data\Repositories\NotificationRepository;
use App\Data\Repositories\BaseRepository;

class ReportsRepository extends BaseRepository
{

    protected 
        $user_info,
        $users,
        $usersData,
        $select_users,
        $user,
        $incident_report,
        $user_reports,
        $sanction_type,
        $sanction_types,
        $sanction_levels,
        $report_response,
        $sanction_level,
        $logs,
        $notification_repo;

    public function __construct(
        UserInfo $user_info,
        Users $users,
        UsersData $usersData,
        SelectUsers $select_users,
        User $user,
        SanctionType $sanction_type,
        SanctionLevel $sanction_level,
        SanctionTypes $sanction_types,
        SanctionLevels $sanction_levels,
        ReportResponse $report_response,
        UserReport $user_reports,
        IncidentReport $incident_report,
        LogsRepository $logs_repo,
        NotificationRepository $notificationRepository
    ) {
        $this->user_info = $user_info;
        $this->usersData = $usersData;
        $this->users = $users;
        $this->select_users = $select_users;
        $this->user = $user;
        $this->user_reports = $user_reports;
        $this->sanction_level = $sanction_level;
        $this->sanction_type = $sanction_type;
        $this->sanction_levels = $sanction_levels;
        $this->sanction_types = $sanction_types;
        $this->report_response = $report_response;
        $this->incident_report = $incident_report;
        $this->logs = $logs_repo;
        $this->notification_repo = $notificationRepository;
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
                    'title' => "sanction type is not set.",
                ]);
            }
            if (!isset($data['sanction_level_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "sanction level is not set.",
                ]);
            }
        }   else{
            if (isset($data['id'])) {
                $does_exist = $this->user_reports->find($data['id']);

                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'Request IR does not exist.',
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
                $auth_id=auth()->user()->id;
                $auth = $this->user->find($auth_id);
                $logged_data = [
                    "user_id" => $auth_id,
                    "action" => "Update",
                    "affected_data" => $auth->full_name."[".$auth->access->name."] Updated the Incident Report filed by  ".$reports->issued_by->full_name."[".$reports->issued_by->position."] to ".$reports->issued_to->full_name."[".$reports->issued_to->position."]"
                ];
                $this->logs->logsInputCheck($logged_data);
                $notification_type = 'reports.update';
            } else{
                $reports = $this->user_reports->init($this->user_reports->pullFillable($data));
                $filed_by = $this->user->find($data['filed_by']);
                $filed_to = $this->user->find($data['user_reports_id']);
                $sanctiont = $this->sanction_type->find($data['sanction_type_id']);
                $sanctionl = $this->sanction_level->find($data['sanction_level_id']);
                $logged_data = [
                    "user_id" => $data['filed_by'],
                    "action" => "Post",
                    "affected_data" => $filed_by->full_name."[".$filed_by->access->name."] Filed an Incident Report to ".$filed_to->full_name."[".$filed_to->access->name."] with a Sanction type of ".$sanctiont->text." and a Sanction Level of ".$sanctionl->text."."
                ];
                $this->logs->logsInputCheck($logged_data);
                $notification_type = 'reports.create';
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

        // trigger notification
        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $reports->filed_by,
            'recipient_id' => $reports->user_reports_id,
            'type' => $notification_type,
            'type_id' => $reports->id,
            'endpoint' => $data['endpoint']
        ]);

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Added/Updated an IR.",
            "meta"        => [
                "data" => $reports,
                "logs" => $logged_data
            ]
        ]);
        
    }

    public function deleteReport($data = [])
    {
        $record = $this->user_reports->find($data['id']); 
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        if(!isset($auth)){
            return $this->setResponse([
                'code'  => 500,
                'title' => "No user was logged in.",
            ]);
        }
        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Incident Report not found"
            ]);
        }
        $filed_to = $this->user->find($record->issued_to->id);    
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
        }else{
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Delete",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Deleted an Incident Report filed to ".$filed_to->full_name."[".$filed_to->access->name."]."
            ];
            $this->logs->logsInputCheck($logged_data);
    
        }

        // trigger notification
        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $record->filed_by,
            'recipient_id' => $record->user_reports_id,
            'type' => 'reports.delete',
            'type_id' => $record->id,
            'endpoint' => $data['endpoint']
        ]);
        
        return $this->setResponse([
            "code"        => 200,
            "title"       => "Incident Report deleted",
            "description" => "Incident Report deleted successfully.",
            "meta"        => [
                "data" => $record,
                'logs' => $logged_data
            ],
            "parameters" => [
                'report_id' => $data['id']
            ]
        ]);

    }

    public function deleteStype($data = [])
    {
        $record = $this->sanction_type->find($data['id']);
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        if(!isset($auth)){
            return $this->setResponse([
                'code'  => 500,
                'title' => "No user was logged in.",
            ]);
        }
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
        }else{
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Delete",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Deleted a Sanction Type [".$record->text."]"
            ];
            $this->logs->logsInputCheck($logged_data);
    
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Sanction Type deleted",
            "description" => "Sanction Type deleted successfully.",
            "meta" => [
                'data' => $record,
                'logs' => $logged_data
            ],
            "parameters" => [
                'sanction_id' => $data['id']
            ]
        ]);

    }

    public function deleteSlevel($data = [])
    {
        $record = $this->sanction_level->find($data['id']);
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        if(!isset($auth)){
            return $this->setResponse([
                'code'  => 500,
                'title' => "No user was logged in.",
            ]);
        }
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
        }else{
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Delete",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Deleted a Sanction Level [".$record->text."]"
            ];
            $this->logs->logsInputCheck($logged_data);
    
        }


        return $this->setResponse([
            "code"        => 200,
            "title"       => "Sanction Level deleted",
            "description" => "Sanction Level deleted successfully.",
            "meta"        => [
                "data" => $record,
                "logs" => $logged_data
            ],
            "parameters"        => [
                "sanction_id" => $data['id']
            ]
        ]);

    }



      public function fetchUserReport($data = [])
    {
        
       $meta_index = "reports";
        $parameters = [];
        $count      = 0;
        

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "reports";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "user_reports_id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['user_reports_id'] = $data['id'];

        }
        $count_data = $data;
        $result = $this->fetchGeneric($data, $this->incident_report);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Reports are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->incident_report->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Users with Reports",
            "description"=>"Users With Incident Reports",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,  
            ],
            "parameters" => $parameters,
        ]);
    }

     public function addSanctionType($data = [])
    {
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        $sanction;
        $param=null;
        $title;
        // data validation
        if (!isset($data['id'])) {
            if (!isset($data['type_number'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Type Number is not set.",
                ]);
            }

            if (!isset($data['type_description'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Type Description is not set.",
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
            $sanction = $sanctionType->type_description;
            $sanctionType->save();
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Update",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Updated a Sanction Type from [".$sanction."] to [".$data['type_description']."]."
            ];
            $this->logs->logsInputCheck($logged_data);
            $param=$data['id'];
            $title="Sucessfully Edited a Sanction Type";
        } else{
            $sanctionType = $this->sanction_type->init($this->sanction_type->pullFillable($data));
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Post",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Added a Sanction Type [".$data['type_description']."]"
            ];
            $this->logs->logsInputCheck($logged_data);
            $title="Successfully Added a Sanction Type";
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
            "title"      => $title,
            "meta"        => [
                "data" => $sanctionType,
                "logs" => $logged_data
            ],
            "parameters"=>$param
        ]);
        
    }


 public function addSanctionLevel($data = [])
    {
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        $sanction;
        $param=null;
        $title;
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
            $sanction = $sanctionLevel->level_description;
            $sanctionLevel->save();
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Update",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Updated a Sanction Level from [".$sanction."] to [".$data['level_description']."]."
            ];
            $this->logs->logsInputCheck($logged_data);
            $param=$data['id'];
            $title= "Successfully Edited a Sanction Level.";
        } else{
            $sanctionLevel = $this->sanction_level->init($this->sanction_level->pullFillable($data));
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Post",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Added a Sanction Level [".$data['level_description']."]"
            ];
            $this->logs->logsInputCheck($logged_data);
            $title= "Successfully Added a Sanction Level.";
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
            "title"      => $title,
            "meta"        => [
                "data" => $sanctionLevel,
                "logs" => $logged_data
            ],
            "parameters"=>$param
        ]);
        
    }

    public function userResponse($data = [])
    {
        // data validation
        $auth_id=auth()->user()->id;  
        $auth = $this->user->find($auth_id);
        $param = null;
        $title = null;
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
            if (isset($data['id'])) {
                $does_exist = $this->report_response->find($data['id']);
                if (!$does_exist) {
                    return $this->setResponse([
                        'code'  => 500,
                        'title' => 'IR not found.',
                    ]);
                }
            }
        }
        if (isset($data['id'])) {
            $response = $this->report_response->find($data['id']);
            $response->save();
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "update",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Updated an IR Response."
            ];
            $this->logs->logsInputCheck($logged_data);
            $param=$data['user_response_id'];
            $title= "Successfully Edited an IR response.";
            $notification_type = 'reports.edit_response';
        } else{
            $response = $this->report_response->init($this->report_response->pullFillable($data));
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "create",
                "affected_data" => $auth->full_name."[".$auth->access->name."] Responded to an Incident Report ."
            ];
            $this->logs->logsInputCheck($logged_data);
            $title= "Successfully Added an IR response.";
            $notification_type = 'reports.add_response';
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

        // fetch report related to response
        $report = $this->user_reports->find($response->user_response_id); 

        // trigger notification
        $notification = $this->notification_repo->triggerNotification([
            'sender_id' => $auth->id,
            'recipient_id' => isset($report) ? $report->filed_by : 0,
            'type' => $notification_type,
            'type_id' => $response->id,
            'endpoint' => $data['endpoint']
        ]);

        return $this->setResponse([
            "code"       => 200,
            "title"      => $title,
            "meta"        => [
                "data" => $response,
                "logs" => $logged_data
            ],
            "parameters"=>$param
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
            "title"      => "Successfully retrieved All Users",
            "description"=>"All Users",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function userFiledIR($data = [])
    {
       $meta_index = "reports";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "reports";
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
            "parameters" => $parameters,
            
        ]);
    }

    public function getAllUserUnder($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count      = 0;
         

        $count_data = $data;
        $data['relations'] = ["accesslevel","accesslevelhierarchy"];   
        $result = $this->fetchGeneric($data, $this->usersData);
        $results=[];
        $keys=0;
        $last_child=null;
        // return $this->setResponse([
        //     'code'       => 404,
        //     'title'      => "No users found",
        //     "meta"       => [
        //         $meta_index => $result,
        //     ],
        //     "parameters" => $parameters,
        // ]);
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
    public function getSanctionTypesSearch($data = [])
    {
        $meta_index = "";
        $parameters = [];
        $count      = 0;

        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }
        $result = $this->sanction_types;

        $meta_index = "sanction_types";
        $parameters = [
            "query" => $data['query'],
        ];

        // if (isset($data['target'])) {
        //     foreach ((array) $data['target'] as $index => $column) {
        //     if (str_contains($column, "type_description")) {
        //         $data['target'][] = 'type_description';
        //         unset($data['target'][$index]);
        //     }
        // }
        // }
       
       
        $count_data = $data;
        $count_data['search'] = true;
         $result = $this->genericSearch($data, $result)->get()->all();

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
    public function getSanctionLevelsSearch($data = [])
    {
        $meta_index = "";
        $parameters = [];
        $count      = 0;

        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }
        $result = $this->sanction_levels;

        $meta_index = "sanction_levels";
        $parameters = [
            "query" => $data['query'],
        ];
       
        $count_data = $data;
        $count_data['search'] = true;
         $result = $this->genericSearch($data, $result)->get()->all();

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Sanction Level are found",
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
            "description"=>"Sanction level",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }

    public function getAll_Ir($data = [])
    {
       $meta_index = "reports";
        $parameters = [];
        $count      = 0;

        $count_data = $data;
        $data['relations'] = [];   
        $result = $this->fetchGeneric($data, $this->incident_report);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No Reports are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
       
        $count = $this->countData($count_data, refresh_model($this->incident_report->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved Users with Reports",
            "description"=>"Users With Incident Reports",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count
            ],
            
            
        ]);
    }





}
