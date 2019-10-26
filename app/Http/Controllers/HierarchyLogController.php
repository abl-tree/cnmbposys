<?php

namespace App\Http\Controllers;

use App\Data\Repositories\HierarchyLogRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HierarchyLogController extends BaseController
{
    protected $hierarchy_log_repo;

    public function __construct(
        HierarchyLogRepository $hierarchyLogRepository
    ) {
        $this->hierarchy_log_repo = $hierarchyLogRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->hierarchy_log_repo->fetchAll($data))->json();
    }


    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->hierarchy_log_repo->define($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data["id"] = $id;
        return $this->absorb($this->hierarchy_log_repo->define($data))->json();
    }

    // public function count(Request $request)
    // {
    //     $data = $request->all();
    //     return $this->absorb($this->leave_slot_repo->countLeaveSlots($data))->json();
    // }

    // public function create(Request $request)
    // {
    //     $data = $request->all();
    //     return $this->absorb($this->leave_slot_repo->defineLeaveSlot($data))->json();
    // }

    // public function bulk(Request $request)
    // {
    //     $data = $request->all();
    //     $success = [];
    //     $error = [];

    //     if (!isset($data['leave_slots'])) {
    //         return $this->setResponse([
    //             'code' => 500,
    //             'title' => 'Leave slots are not set.',
    //             'parameters' => $data,
    //         ])->json();
    //     }

    //     foreach ((array) $data['leave_slots'] as $leave_slot_data) {
    //         $leave_slot = $this->absorb($this->leave_slot_repo->defineLeaveSlot($leave_slot_data))->json();

    //         if ($leave_slot->getStatusCode() == 200) {
    //             $success[] = $leave_slot->getData();
    //         } else {
    //             $error[] = $leave_slot->getData();
    //         };
    //     }

    //     return $this->setResponse([
    //         'code' => 200,
    //         'title' => 'Successfully defined leave slots',
    //         'meta' => [
    //             'success' => $success,
    //             'success_count' => count($success),
    //             'error' => $error,
    //             'error_count' => count($error),
    //         ],
    //         'parameters' => $data,
    //     ])->json();

    // }

    // public function delete(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $data['id'] = $id;

    //     if (!isset($data['id']) ||
    //         !is_numeric($data['id']) ||
    //         $data['id'] <= 0) {
    //         return $this->setResponse([
    //             'code' => 500,
    //             'title' => "Leave slot ID is not set.",
    //         ]);
    //     }

    //     return $this->absorb($this->leave_slot_repo->deleteLeaveSlot($data))->json();
    // }

    // public function fetch(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $data['id'] = $id;

    //     if (!isset($data['id']) ||
    //         !is_numeric($data['id']) ||
    //         $data['id'] <= 0) {
    //         return $this->setResponse([
    //             'code' => 500,
    //             'title' => "Leave slot ID is invalid.",
    //         ]);
    //     }

    //     return $this->absorb($this->leave_slot_repo->fetchLeaveSlot($data))->json();
    // }

    // public function update(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $data['id'] = $id;

    //     return $this->absorb($this->leave_slot_repo->defineLeaveSlot($data))->json();
    // }

    // public function search(Request $request)
    // {
    //     $data = $request->all();
    //     return $this->absorb($this->leave_slot_repo->searchLeaveSlot($data))->json();
    // }

}
