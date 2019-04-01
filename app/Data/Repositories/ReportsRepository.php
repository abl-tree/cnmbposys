<?php
namespace App\Data\Repositories;

use App\Data\Models\UserInfo;
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
        $user,
        $user_reports,
        $sanction_type,
        $report_response,
        $sanction_level;

    public function __construct(
        UserInfo $user_info,
        User $user,
        SanctionType $sanction_type,
        SanctionLevel $sanction_level,
        ReportResponse $report_response,
        UserReport $user_reports
    ) {
        $this->user_info = $user_info;
        $this->user = $user;
        $this->user_reports = $user_reports;
        $this->sanction_level = $sanction_level;
        $this->sanction_type = $sanction_type;
        $this->report_response = $report_response;
    } 

    public function getAllReports($data = [])
    {
        $meta_index = "ReportData";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "ReportData";
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
             if($value->reports!="[]"){        
                $results->$key = $value;
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
       $meta_index = "ReportData";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "ReportData";
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
             if($value->reports!="[]"){        
                $results->$key = $value;
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




}
