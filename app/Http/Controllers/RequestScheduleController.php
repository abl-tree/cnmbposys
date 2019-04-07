<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\RequestScheduleRepository;

class RequestScheduleController extends BaseController
{
    protected $request_schedule_repo;

    public function __construct(
        RequestScheduleRepository $requestScheduleRepository
    ){
        $this->request_schedule_repo = $requestScheduleRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->request_schedule_repo->fetchRequestSchedule($data))->json();
    }

    public function fetchByApplicant(Request $request, $id){
        
        $data = $request->all();
        $data['where']  = [
            [
                "target"   => "applicant",
                "operator" => "=",
                "value"    => $id,
            ]
        ];
        return $this->absorb($this->request_schedule_repo->fetchRequestSchedule($data))->json();
    }

    public function fetchByRequestedBy(Request $request, $id){
        
        $data = $request->all();
        $data['where']  = [
            [
                "target"   => "requested_by",
                "operator" => "=",
                "value"    => $id,
            ]
        ];
        return $this->absorb($this->request_schedule_repo->fetchRequestSchedule($data))->json();
    } 

    public function fetchByManagedBy(Request $request, $id){
        
        $data = $request->all();
        $data['where']  = [
            [
                "target"   => "managed_by",
                "operator" => "=",
                "value"    => $id,
            ]
        ];
        return $this->absorb($this->request_schedule_repo->fetchRequestSchedule($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->request_schedule_repo->defineRequestSchedule($data))->json();
    }

    public function delete(Request $request, $id)
    {
        $data       = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "Request schedule ID is not set.",
            ]);
        }

        return $this->absorb($this->request_schedule_repo->deleteRequestSchedule($data))->json();
    }


    public function fetch(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "Request schedule ID is invalid.",
            ]);
        }

        return $this->absorb($this->request_schedule_repo->fetchRequestSchedule($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->request_schedule_repo->defineRequestSchedule($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->request_schedule_repo->searchRequestSchedule($data))->json();
    }

}
