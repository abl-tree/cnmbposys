<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\AgentRepository;

class AgentController extends BaseController
{
    protected $agent_repo;

    public function __construct(
        AgentRepository $agentRepository
    ){
        $this->agent_repo = $agentRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->agent_repo->fetchAgent($data))->json();
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
                'title' => " ID is invalid.",
            ]);
        }

        return $this->absorb($this->agent_repo->fetchAgent($data))->json();
    }

}
