<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\AgentScheduleRepository;

class AgentScheduleController extends BaseController
{
    protected $agent_schedule_repo;

    public function __construct(
        AgentScheduleRepository $agentScheduleRepository
    ){
        $this->agent_schedule_repo = $agentScheduleRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->fetchAgentSchedule($data))->json();
    }

    public function bulkScheduleInsertion(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->bulkScheduleInsertion($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->defineAgentSchedule($data))->json();
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
                'title' => "Schedule ID is not set.",
            ]);
        }

        return $this->absorb($this->agent_schedule_repo->deleteAgentSchedule($data))->json();
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
                'title' => "Schedule ID is invalid.",
            ]);
        }

        return $this->absorb($this->agent_schedule_repo->fetchAgentSchedule($data))->json();
    }

    public function fetchAllAgentsWithSchedule(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->agent_schedule_repo->fetchAllAgentsWithSchedule($data))->json();
    }

    public function fetchAgentWithSchedule(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->agent_schedule_repo->fetchAllAgentsWithSchedule($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->agent_schedule_repo->defineAgentSchedule($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->agent_schedule_repo->searchAgentSchedule($data))->json();
    }


}
