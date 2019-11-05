<?php
namespace App\Data\Repositories;

use App\Data\Models\Coaching;
use App\User;
use App\Data\Repositories\BaseRepository;
use Illuminate\Support\Facades\Storage;

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
            if (!isset($data['image'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "proof  is not set.",
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
            if($coachingdata==null){
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Coach not found.",
                ]);
            }
            if(strtolower($coachingdata->filed_to_action)!=='approved'){
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Coaching Not Yet Approved.",
                ]);
            }
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
            "title"      => "Successfully verified coaching.",
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

        if(!$coachingdata){
            return $this->setResponse([
                'code'  => 422,
                'title' => "Action not processed, data is already outdated.",
            ]);
        }
        // added validation
        // throw error if coaching status == verified
        if($coachingdata->status == "verified"){
            return $this->setResponse([
                'code'  => 422,
                'title' => "Action not processed, data is already outdated.",
            ]);
        }

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
            "title"      => "Successfully updated approval.",
            "meta"        => [
                "status" => $coachingdata,
            ]
        ]);
            
        
    }

    public function revertVerify($data = [])
    {
            if (!isset($data['id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "coaching id is not set.",
                ]);
            }
            $coachingdata = $this->coaching->find($data['id']);
            if($coachingdata==null){
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Coach not found.",
                ]);
            }
            if($coachingdata->verified_by==NULL){
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Coach is not yet verified.",
                ]);
            }
            // if($coachingdata->verified_by!==auth()->user()->id){
            //     return $this->setResponse([
            //         'code'  => 500,
            //         'title' => "You are not the one who verified this coach.",
            //     ]);
            // }
            $data['verified_by']=NULL;
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
            "title"      => "Successfully reverted verified coaching.",
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

    public function update($data = [])
    {
        $coachingdata = $this->coaching->find($data['id']);
        if($coachingdata==null){
            return $this->setResponse([
                'code'  => 500,
                'title' => "Coach not found.",
            ]);
        }
        if($coachingdata->filed_by!==auth()->user()->id){
            return $this->setResponse([
                "code"       => 500,
                "title"      => "Action Not Valid",
                "meta"        => [
                    "errors" => "You are not the user who made the coaching.",
                ]
            ]);
        }
        
        if($coachingdata->status != "pending"){
            return $this->setResponse([
                "code"       => 422,
                "title"      => "Action not processed, data is already outdated.",
            ]);
        }
        
        if(isset($data["image"])){
            if (isset($data['imageName'])) {
                $url = $coachingdata->img_proof_url;
                $file_name = basename($url);
                Storage::delete('images/' . $file_name);

                define('UPLOAD_DIR', 'storage/images/');
                $file = request()->image->move(UPLOAD_DIR, $data['imageName']);
                $url = asset($file);
                $data['img_proof_url'] = $url;
            }
        }

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

    public function delete($data = [])
    {
        $coachingdata = $this->coaching->find($data['id']);
        if($coachingdata==null){
            return $this->setResponse([
                'code'  => 500,
                'title' => "Coach not found.",
            ]);
        }
        if($coachingdata->status != "pending"){
            return $this->setResponse([
                "code"       => 422,
                "title"      => "Action not processed, data is already outdated.",
            ]);
        }
        
        if (!$coachingdata->delete()) {
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
            "title"      => "Successfully deleted a Coach.",
            "meta"        => [
                "status" => $coachingdata,
            ]
        ]);
            
        
    }


   


}