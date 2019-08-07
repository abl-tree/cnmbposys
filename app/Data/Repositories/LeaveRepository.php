<?php

namespace App\Data\Repositories;

use App\Data\Models\AgentSchedule;
use App\Data\Models\Attendance;
use App\Data\Models\Leave;
use App\Data\Models\LeaveCredit;
use App\Data\Repositories\BaseRepository;
use App\User;

class LeaveRepository extends BaseRepository
{

    protected $leave,
    $leave_credit,
    $agent_schedule,
    $attendance,
        $user;

    public function __construct(
        Leave $leave,
        LeaveCredit $leaveCredit,
        AgentSchedule $agentSchedule,
        Attendance $attendance,
        User $user
    ) {
        $this->leave = $leave;
        $this->leave_credit = $leaveCredit;
        $this->agent_schedule = $agentSchedule;
        $this->attendance = $attendance;
        $this->user = $user;
    }

    public function setLeaveApproval($data = [])
    {
        //fetch leave
        $leave = refresh_model($this->leave->getModel())
            ->where('id', $data['id'])
            // ->where('allowed_access', $data['user_access']) //to be reworked
            ->first();

        if (!$leave) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No leaves found.",
            ]);
        }

        if ($leave->status != 'pending') {
            return $this->setResponse([
                'code' => 500,
                'title' => "Leave is already {$leave->status}",
            ]);
        }

        /**
         * Check if user is OM
         * &&
         * Check if slots for leave approval are still available
         */
        if ($data['user_access'] == 15) {
            //fetch user data
            $operations_manager = refresh_model($this->user->getModel())->find($data['approved_by']);

            //fetch all schedules
            $schedule_slots = refresh_model($this->agent_schedule->getModel())
                ->where('user_id', $leave->user_id)
                ->where('start_event', '>=', $leave->start_event)
                ->where('end_event', '<=', $leave->end_event)
                ->get()->all();

            //check if a leave slot is full
            foreach ($schedule_slots as $slot) {
                $leave_slot = $operations_manager->leave_slots
                    ->where('leave_type', $leave->leave_type)
                    ->where('date', '>=', $slot->start_event)
                    ->where('date', '<=', $slot->end_event)
                    ->first();

                if (!isset($leave_slot) || $leave_slot->value <= 0) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "Leave slots for {$slot->start_event} are already full.",
                    ]);
                }
            }

            //decrement leave slots
            foreach ($schedule_slots as $slot) {
                $leave_slot = $operations_manager->leave_slots
                    ->where('leave_type', $leave->leave_type)
                    ->where('date', '>=', $slot->start_event)
                    ->where('date', '<=', $slot->end_event)
                    ->first();

                $leave_slot->update([
                    'value' => --$leave_slot->value,
                ]);
            }
        }

        if ($data['status'] == "approved") {
            //fetch available leave credits
            $leave_credits = $this->leave_credit
                ->where('user_id', $leave->user_id)
                ->where('leave_type', $leave->leave_type)
                ->first();

            if (!$leave_credits) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Employee does not have leave credits.",
                ]);
            }

            //count total leave days according to schedule
            $total_days = refresh_model($this->agent_schedule->getModel())
                ->where('user_id', $leave->user_id)
                ->where('start_event', '>=', $leave->start_event)
                ->where('end_event', '<=', $leave->end_event)
                ->count();

            if ($leave_credits->value < $total_days) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Employee does not have enough leave credits.",
                    'parameters' => [
                        'credits' => [
                            'available' => $leave_credits->value,
                            'needed' => $total_days,
                        ],
                    ],
                ]);
            }

            //update leave credits
            $leave_credits->update([
                'value' => $leave_credits->value - $total_days,
            ]);

            //fetch raw  agent schedules affected by the leave (query builder format)
            $raw_schedules = refresh_model($this->agent_schedule->getModel())
                ->where('user_id', $leave->user_id)
                ->where('start_event', '>=', $leave->start_event)
                ->where('end_event', '<=', $leave->end_event);

            //update agent schedules
            $raw_schedules->update([
                'leave_id' => $leave->id,
            ]);

            //fetch schedules
            $schedules = $raw_schedules->get()->all();

            //add attendance for leave schedules
            foreach ($schedules as $schedule) {
                refresh_model($this->attendance->getModel())
                    ->save([
                        'schedule_id' => $schedule->id,
                        'time_in' => $schedule->start_event,
                        'time_out' => $schedule->end_event,
                        'is_leave' => 1,
                    ]);
            }

        }

        return $this->defineLeave([
            'id' => $data['id'],
            'status' => $data['status'],
            'approved_by' => $data['approved_by'],
        ]);
    }

    public function cancelLeave($data = [])
    {
        if (!isset($data['cancel_event'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Cancel event is not set.",
            ]);
        }

        return $this->revertLeave($data);
    }

    public function defineLeave($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['user_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not set.",
                ]);
            }

            if (!isset($data['allowed_access'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Allowed access is not set.",
                ]);
            }

            if (!isset($data['start_event'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Start leave is not set.",
                ]);
            }

            if (!isset($data['end_event'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "End leave is not set.",
                ]);
            }

            if (!isset($data['leave_type'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Leave type is not set.",
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['id'])) {
            $does_exist = $this->leave->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'Leave does not exist.',
                ]);
            }
        }

        if (isset($data['user_id'])) {
            $does_exist = $this->user->find($data['user_id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'User does not exist.',
                ]);
            }
        }

        // existence check

        // insertion

        if (isset($data['id'])) {
            $leave = $this->leave->find($data['id']);
        } else {
            $leave = $this->leave->init($this->leave->pullFillable($data));
        }

        if (!$leave->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $leave->errors(),
                ],
            ]);
        }

        //set auto approved leave
        if (isset($data['isApproved']) && $data['isApproved']) {
            $this->setLeaveApproval([
                'id' => $leave->id,
                'status' => 'approved',
                'user_access' => $leave->allowed_access,
                'approved_by' => $data['generated_by'],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a leave.",
            "parameters" => $leave,
        ]);

        // insertion

    }

    public function deleteLeave($data = [])
    {
        $leave = $this->leave->find($data['id']);

        if (!$leave) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Leave not found",
            ]);
        }

        //revert leave data
        $this->revertLeave($data);

        if (!$leave->delete()) {
            return $this->setResponse([
                "code" => 500,
                "message" => "Deleting leave was not successful.",
                "meta" => [
                    "errors" => $leave->errors(),
                ],
                "parameters" => [
                    'title_id' => $data['id'],
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Leave deleted",
            "description" => "A leave was deleted.",
            "parameters" => $leave,
        ]);

    }

    public function revertLeave($data = [])
    {
        $leave = $this->leave->find($data['id']);

        if (!$leave) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Leave not found",
            ]);
        }

        $data['start_leave'] = $data['cancel_event'] ?? $leave->start_event;

        if ($leave->status == 'approved') {
            //raw schedules (query builder format)
            $schedules = $this->agent_schedule
                ->where('leave_id', $leave->id)
                ->where('start_event', '>=', $data['start_leave'])
                ->where('end_event', '<=', $leave->end_event);

            //remove attendance
            foreach ($schedules->get()->all() as $schedule) {
                refresh_model($this->attendance->getModel())
                    ->where('schedule_id', $schedule->id)
                    ->where('is_leave', '!=', 0)
                    ->delete();
            }

            //remove schedule-leave tie
            $schedules->update([
                'leave_id' => null,
            ]);

            //fetch leave credits
            $leave_credits = $this->leave_credit
                ->where('user_id', $leave->user_id)
                ->where('leave_type', $leave->leave_type)
                ->first();

            //return leave_credits
            if ($leave_credits) {

                //count total leave days according to schedule
                $total_days = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $data['start_leave'])
                    ->where('end_event', '<=', $leave->end_event)
                    ->count();

                $leave_credits->update([
                    'value' => $leave_credits->value + $total_days,
                ]);
            }
        }

        return $this->defineLeave([
            'id' => $data['id'],
            'status' => 'cancelled',
        ]);
    }

    public function fetchLeave($data = [])
    {
        $meta_index = "leaves";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "leave";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['leave_id'] = $data['id'];

        }

        //filter leaves by status
        if (isset($data['status'])) {
            $data['where'][] = [
                "target" => "status",
                "operator" => "=",
                "value" => $data['status'],
            ];
        }

        //set relations
        $data['relations'][] = 'user';

        //fetch user if set
        if (isset($data['user_id']) && is_numeric($data['user_id'])) {
            $data['where'][] = [
                "target" => "user_id",
                "operator" => "=",
                "value" => $data['user_id'],
            ];
        }

        /**
         * Set access level filter
         * (to be reworked)
         */
        if ($data['user_access'] == 15) {
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => ">=",
                "value" => 15,
            ];
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => "<=",
                "value" => 17,
            ];
        } else if ($data['user_access'] >= 12 && $data['user_access'] <= 14) {
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => ">=",
                "value" => 12,
            ];
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => "<=",
                "value" => 14,
            ];
        } else if ($data['user_access'] <= 3) {
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => ">",
                "value" => 0,
            ];
        } else {
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => "=",
                "value" => $data['user_access'],
            ];
        }

        $count_data = $data;

        $result = $this->fetchGeneric($data, $this->leave);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No leaves are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->leave->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved leaves",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchLeave($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->leave;

        $meta_index = "leaves";
        $parameters = [
            "query" => $data['query'],
        ];

        //filter leaves by status
        if (isset($data['status'])) {
            $data['where'][] = [
                "target" => "status",
                "operator" => "=",
                "value" => $data['status'],
            ];
        }

        //set relations
        $data['relations'][] = 'user';

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No leaves are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->leave->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched leaves",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
