<?php

namespace App\Http\Controllers;

use App\Data\Repositories\LeaveRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class LeaveController extends BaseController
{
    protected $leave_repo;

    public function __construct(
        LeaveRepository $leaveRepository
    ) {
        $this->leave_repo = $leaveRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        $data['user_access'] = $request->user()->access->id;
        return $this->absorb($this->leave_repo->fetchLeave($data))->json();
    }

    public function approval(Request $request, $action, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['status'] = $action == "approve" ? 'approved' : 'rejected';
        $data['approved_by'] = $request->user()->id;
        $data['user_access'] = $request->user()->access->id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Leave ID is invalid.",
            ]);
        }

        return $this->absorb($this->leave_repo->setLeaveApproval($data))->json();
    }

    public function cancel(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Leave ID is invalid.",
            ]);
        }

        return $this->absorb($this->leave_repo->cancelLeave($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $data['generated_by'] = $request->user()->id;
        if (isset($data['status']) && strtolower($data['status']) == 'approved') {
            unset($data['status']);
            $data['isApproved'] = true;
            $data['allowed_access'] = $request->user()->access->id;
        }
        return $this->absorb($this->leave_repo->defineLeave($data))->json();
    }

    public function delete(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['user_access'] = $request->user()->access->id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Leave ID is not set.",
            ]);
        }

        return $this->absorb($this->leave_repo->deleteLeave($data))->json();
    }

    public function fetch(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['user_access'] = $request->user()->access->id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Leave ID is invalid.",
            ]);
        }

        return $this->absorb($this->leave_repo->fetchLeave($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['user_access'] = $request->user()->access->id;
        if (isset($data['status']) && strtolower($data['status']) == 'approved') {
            unset($data['status']);
            $data['isApproved'] = true;
            $data['allowed_access'] = $request->user()->access->id;
        }

        return $this->absorb($this->leave_repo->defineLeave($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $data['user_access'] = $request->user()->access->id;
        return $this->absorb($this->leave_repo->searchLeave($data))->json();
    }

}
