<?php

namespace App\Http\Controllers;

use App\Data\Repositories\LeaveSlotRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class LeaveSlotController extends BaseController
{
    protected $leave_slot_repo;

    public function __construct(
        LeaveSlotRepository $leaveSlotRepository
    ) {
        $this->leave_slot_repo = $leaveSlotRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_slot_repo->fetchLeaveSlot($data))->json();
    }

    public function count(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_slot_repo->countLeaveSlots($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_slot_repo->defineLeaveSlot($data))->json();
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
                'title' => "Leave slot ID is not set.",
            ]);
        }

        return $this->absorb($this->leave_slot_repo->deleteLeaveSlot($data))->json();
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
                'title' => "Leave slot ID is invalid.",
            ]);
        }

        return $this->absorb($this->leave_slot_repo->fetchLeaveSlot($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->leave_slot_repo->defineLeaveSlot($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->leave_slot_repo->searchLeaveSlot($data))->json();
    }

}
