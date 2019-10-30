<?php
namespace App\Data\Repositories;

use App\Data\Models\Coaching;
use App\User;
use App\Data\Repositories\BaseRepository;

class CoachingRepository extends BaseRepository
{

    protected $coaching;

    public function __construct(
        Coaching $coaching
        
    ) {
        $this->coaching = $coaching;
       
    } 
    public function create($data = [])
    {
        // data validation
             
            if (!isset($data['filed_to'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "filed_to is not set.",
                ]);
            }
            if (!isset($data['filed_by'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "filed_by  is not set.",
                ]);
            }
            if (!isset($data['status'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "status  is not set.",
                ]);
            }
            if (!isset($data['sched_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "sched_id  is not set.",
                ]);
            }
            if (!isset($data['remarks'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "remarks  is not set.",
                ]);
            }
            if (isset($data['imageName'])) {
                define('UPLOAD_DIR', 'storage/images/');
                $file = request()->image->move(UPLOAD_DIR, $data['imageName']);
                $url = asset($file);
                $data['img_proof_url'] = $url;
            }
            $coachingdata = $this->coaching->init($this->coaching->pullFillable($data));
             
            $coachingdata->save($data);

        if (!$coachingdata->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $coachingdata->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Filed Successfully",
            "description" => "Coaching",
            "meta"       => [
                "metadata" => $coachingdata,
            ],
        ]);
        
    }
    public function verifyCoach($data = [])
    {
            if (!isset($data['verified_by'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "verified_by id is not set.",
                ]);
            }
            if (!isset($data['status'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "status is not set.",
                ]);
            }
            $coachingdata = $this->coaching->find($data['id']);
            $coachingdata->save($data);

        if (!$coachingdata->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $coachingdata->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully updated a coach.",
            "meta"        => [
                "status" => $coachingdata,
            ]
        ]);
            
        
    }
    public function agentAction($data = [])
    {
            if (!isset($data['filed_to_action'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "filed_to_action id is not set.",
                ]);
            }
            $coachingdata = $this->coaching->find($data['id']);
            $coachingdata->save($data);

        if (!$coachingdata->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $coachingdata->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully Updates Coach",
            "meta"        => [
                "status" => $coachingdata,
            ]
        ]);
            
        
    }

    public function coachDetails($data = [])
    {
        $meta_index = "coach";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "coach";
            $data['single'] = false;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['coach_id'] = $data['id'];

        }

        $count_data = $data;

         $data['relations'] = ["schedule",'verified_by','filed_to','filed_by'];     

        $result = $this->fetchGeneric($data, $this->coaching);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No coaching details found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->coaching->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved coaching details",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }


   


}
