<?php

namespace App\Http\Controllers;

use App\Data\Repositories\AgentScheduleRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AgentScheduleController extends BaseController
{
    protected $agent_schedule_repo;

    public function __construct(
        AgentScheduleRepository $agentScheduleRepository
    ) {
        $this->agent_schedule_repo = $agentScheduleRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();

        if (isset($data['search']) || isset($data['target'])) {
            return $this->absorb($this->agent_schedule_repo->searchAgentSchedule($data))->json();
        } else {
            return $this->absorb($this->agent_schedule_repo->fetchAgentSchedule($data))->json();
        }
    }

    public function excelData(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->excelData($data))->json();
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
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
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
                'code' => 500,
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

    public function update(Request $request, $attendance_id)
    {
        $data = $request->all();
        $data['id'] = $attendance_id;

        return $this->absorb($this->agent_schedule_repo->defineAgentSchedule($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->agent_schedule_repo->searchAgentSchedule($data))->json();
    }

    public function stats(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->agent_schedule_repo->agentScheduleStats($data))->json();
    }

    public function workInfo(Request $request, $option)
    {
        $data = $request->all();

        return $this->absorb($this->agent_schedule_repo->workInfo($data, $option))->json();
    }

    public function conformance(Request $request, $id) 
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->conformance($data, $id))->json();
    }

    public function remarks(Request $request, $id) 
    {
        $data = $request->all();
        return $this->absorb($this->agent_schedule_repo->remarks($data, $id))->json();
    }

}
