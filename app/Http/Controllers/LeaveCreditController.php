<?php

namespace App\Http\Controllers;

use App\Data\Repositories\LeaveCreditRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class LeaveCreditController extends BaseController
{
    protected $leave_credit_repo;

    public function __construct(
        LeaveCreditRepository $leaveCreditRepository
    ) {
        $this->leave_credit_repo = $leaveCreditRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_credit_repo->fetchLeaveCredit($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_credit_repo->defineLeaveCredit($data))->json();
    }

    public function createForAgents(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_credit_repo->defineLeaveCreditForAgents($data))->json();
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
                'title' => "Leave credit ID is not set.",
            ]);
        }

        return $this->absorb($this->leave_credit_repo->deleteLeaveCredit($data))->json();
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
                'title' => "Leave credit ID is invalid.",
            ]);
        }

        return $this->absorb($this->leave_credit_repo->fetchLeaveCredit($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->leave_credit_repo->defineLeaveCredit($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_credit_repo->searchLeaveCredit($data))->json();
    }

}
