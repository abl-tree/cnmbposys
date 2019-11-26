<?php

namespace App\Data\Repositories;

use App\Data\Models\AgentSchedule;
use App\Data\Models\Attendance;
use App\Data\Models\Leave;
use App\Data\Models\LeaveCredit;
use App\Data\Models\LeaveSlot;
use App\Data\Repositories\BaseRepository;
use App\User;
use Carbon\Carbon;
use DateTime;

class LeaveRepository extends BaseRepository
{

    protected $leave,
    $leave_credit,
    $leave_slot,
    $agent_schedule,
    $attendance,
        $user;

    public $no_sort;

    public function __construct(
        Leave $leave,
        LeaveCredit $leaveCredit,
        LeaveSlot $leaveSlot,
        AgentSchedule $agentSchedule,
        Attendance $attendance,
        User $user
    ) {
        $this->leave = $leave;
        $this->leave_credit = $leaveCredit;
        $this->leave_slot = $leaveSlot;
        $this->agent_schedule = $agentSchedule;
        $this->attendance = $attendance;
        $this->user = $user;

        $this->no_sort = [
            'recently_approved.updated_at',
        ];
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
        if (
            strtolower($data['status']) == "approved" &&
            (strtolower($leave->leave_type) == "leave_of_absence" || strtolower($leave->leave_type) == "vacation_leave")
        ) {

            //fetch user data
            $operations_manager = refresh_model($this->user->getModel())->find($data['om_id']);

            //fetch all schedules
            $schedule_slots = refresh_model($this->agent_schedule->getModel())
                ->where('user_id', $leave->user_id)
                ->where('start_event', '>=', $leave->start_event)
                ->where('end_event', '<=', $leave->end_event)
                ->get()->all();

            //check if a leave slot is full
            if ($leave->leave_type == "vacation_leave" || $leave->leave_type == "leave_of_absence") {
                foreach ($schedule_slots as $slot) {
                    $start = substr($slot->start_event, 0, 10);
                    $leave_slot = $operations_manager->leave_slots
                        ->where('leave_type', $leave->leave_type)
                        ->where('date', $start . " 00:00:00")
                        ->first();
                    // dd($leave_slot);
                    if (!isset($leave_slot)) {
                        return $this->setResponse([
                            'code' => 500,
                            'meta' => $operations_manager,
                            'title' => "There are no leave slots for {$start}.",
                        ]);
                    }

                    if ($leave_slot->value <= 0) {
                        return $this->setResponse([
                            'code' => 500,
                            'title' => "Leave slots for {$start} are already full.",
                        ]);
                    }
                }
            }

            //decrement leave slots
            if ($leave->leave_type == "vacation_leave" || $leave->leave_type == "leave_of_absence") {
                foreach ($schedule_slots as $slot) {
                    $start = substr($slot->start_event, 0, 10);
                    $leave_slot = $operations_manager->leave_slots
                        ->where('leave_type', $leave->leave_type)
                        ->where('date', $start . " 00:00:00")
                        ->first();

                    if ($leave->leave_type == "vacation_leave" || $leave->leave_type == "leave_of_absence") {
                        $leave_slot->update([
                            'value' => --$leave_slot->value,
                        ]);
                    }
                }
            }

        }

        if (
            strtolower($data['status']) == "approved" &&
            strtolower($leave->leave_type) != "suspended"
        ) {
            if ($leave->leave_type == "vacation_leave" || $leave->leave_type == "sick_leave") {
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

                //initialize credits needed
                $credits_needed = 0;

                //fetch all schedules hit by leave
                $schedules_hit = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $leave->start_event)
                    ->where('end_event', '<=', $leave->end_event)
                    ->get()->all();

                /**
                 * loop through all schedules hit
                 * to calculate total of hours needed
                 */
                foreach ($schedules_hit as $schedule) {
                    $diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($schedule->start_event), true);
                    $hours = $diff_mins / 60;

                    //total hours summation
                    $credits_needed += $hours;
                }

                if ($leave_credits->value < $credits_needed) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "Employee does not have enough leave credits.",
                        'parameters' => [
                            'credits' => [
                                'available' => $leave_credits->value,
                                'needed' => $credits_needed,
                            ],
                        ],
                    ]);
                }

                /**
                 * loop through all schedules hit
                 * to add conformance
                 */
                foreach ($schedules_hit as $schedule) {
                    $diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($schedule->start_event), true);
                    $hours = $diff_mins / 60;
                    $_hours = 0;

                    $attendance = refresh_model($this->attendance->getModel())
                        ->where('schedule_id', $schedule->id)
                        ->first();

                    if ($attendance) {
                        $_diff_mins = Carbon::parse($attendance->time_out)->diffInMinutes(Carbon::parse($attendance->time_in), true);
                        $_hours = $_diff_mins / 60;
                    }

                    $conformance = (($_hours + $hours) / $hours) * 100;
                    $schedule->save([
                        'conformance' => $conformance,
                    ]);
                }

                //update leave credits
                $leave_credits->update([
                    'value' => $leave_credits->value - $credits_needed,
                ]);

            }

            if ($leave->leave_type == "partial_sick_leave") {
                //fetch available leave credits
                $leave_credits = $this->leave_credit
                    ->where('user_id', $leave->user_id)
                    ->where('leave_type', 'sick_leave')
                    ->first();

                if (!$leave_credits) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "Employee does not have leave credits.",
                    ]);
                }

                //initialize credits needed
                $credits_needed = 0;

                //fetch all schedules hit by leave
                $schedules_hit = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $leave->start_event)
                    ->where('end_event', '<=', $leave->end_event)
                    ->get()->all();

                /**
                 * loop through all schedules hit
                 * to calculate total of hours needed
                 */
                foreach ($schedules_hit as $schedule) {
                    $attendance = refresh_model($this->attendance->getModel())
                        ->where('schedule_id', $schedule->id)
                        ->first();

                    if ($attendance) {
                        $diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($attendance->time_out), true);
                        $hours = $diff_mins / 60;

                        //total hours summation
                        $credits_needed += $hours;

                        //do something if credits are not enough
                        if ($leave_credits->value < $credits_needed) {
                            $attendance->update([
                                'raw_time_out' => $attendance->time_out,
                                'time_out' => Carbon::parse($attendance->time_out)->addMinutes($leave_credits->value * 60),
                            ]);

                            //set credits needed to current leave value to even out data
                            $credits_needed = $leave_credits->value;
                        } else {
                            $attendance->update([
                                'raw_time_out' => $attendance->time_out,
                                'time_out' => Carbon::parse($schedule->end_event),
                            ]);
                        }

                        //update leave credits
                        $leave_credits->update([
                            'value' => $leave_credits->value - $credits_needed,
                        ]);

                        //for conformance

                        $_diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($schedule->start_event), true);
                        $_hours = $diff_mins / 60;

                        $conformance = (($hours + $credits_needed) / $_hours) * 100;
                        $schedule->save([
                            'conformance' => $conformance,
                        ]);
                    }
                }

            }

            if ($leave->leave_type == 'loa1' || $leave->leave_type == 'loa2') {
                //fetch available leave credits
                $leave_credits = $this->leave_credit
                    ->where('user_id', $leave->user_id)
                    ->whereIn('leave_type', $leave->leave_type == 'loa1' ? ['sick_leave', 'vacation_leave'] : ['vacation_leave'])
                    ->orderBy('leave_type', $leave->leave_type == 'loa1' ? 'asc' : 'desc')
                    ->get()->all();

                if (!$leave_credits) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "Employee does not have leave credits.",
                    ]);
                }

                //initialize credits needed
                $credits_needed = 0;

                //fetch all schedules hit by leave
                $schedules_hit = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $leave->start_event)
                    ->where('end_event', '<=', $leave->end_event)
                    ->get()->all();

                /**
                 * loop through all schedules hit
                 * to calculate total of hours needed
                 */
                foreach ($schedules_hit as $schedule) {
                    $diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($schedule->start_event), true);
                    $hours = $diff_mins / 60;

                    //total hours summation
                    $credits_needed += $hours;
                }

                $credits_available = 0;
                foreach ($leave_credits as $leave_credit) {
                    $credits_available += $leave_credit->value;
                }

                if ($credits_available < $credits_needed) {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "Employee does not have enough leave credits.",
                        'parameters' => [
                            'credits' => [
                                'available' => $credits_available,
                                'needed' => $credits_needed,
                            ],
                        ],
                    ]);
                }

                /**
                 * loop through all schedules hit
                 * to add conformance
                 */
                foreach ($schedules_hit as $schedule) {
                    $diff_mins = Carbon::parse($schedule->end_event)->diffInMinutes(Carbon::parse($schedule->start_event), true);
                    $hours = $diff_mins / 60;
                    $_hours = 0;

                    $attendance = refresh_model($this->attendance->getModel())
                        ->where('schedule_id', $schedule->id)
                        ->first();

                    if ($attendance) {
                        $_diff_mins = Carbon::parse($attendance->time_out)->diffInMinutes(Carbon::parse($attendance->time_in), true);
                        $_hours = $_diff_mins / 60;
                    }

                    $conformance = (($_hours + $hours) / $hours) * 100;
                    $schedule->save([
                        'conformance' => $conformance,
                    ]);
                }

                //update leave credits
                foreach ($leave_credits as $leave_credit) {
                    if (($leave_credit->value - $credits_needed) >= 0) {
                        $leave_credit->value = $leave_credit->value - $credits_needed;
                    } else {
                        $credits_needed = $credits_needed - $leave_credit->value;
                        $leave_credit->value = 0;
                    }
                    //save newly computed credits
                    $leave_credit->save();
                }
            }

        }

        if (strtolower($data['status']) == "approved") {
            //fetch raw  agent schedules affected by the leave (query builder format)
            $raw_schedules = refresh_model($this->agent_schedule->getModel())
                ->where('user_id', $leave->user_id)
                ->where('start_event', '>=', $leave->start_event)
                ->where('end_event', '<=', $leave->end_event);
            // ->where('leave_id','=',null);

            //fetch schedules
            $schedules = $raw_schedules->get()->all();
            //revert leaves to be overriden
            foreach ($schedules as $schedule) {
                $hit_leave = refresh_model($this->leave->getModel())->find($schedule->leave_id ?? 0);
                if ($hit_leave) {
                    $this->revertLeave([
                        'id' => $leave->id,
                        'cancel_event' => $hit_leave->start_event,
                    ]);
                }
            }

            //update agent schedules
            $raw_schedules->update([
                'leave_id' => $leave->id,
            ]);

            //add attendance for leave schedules
            // foreach ($schedules as $schedule) {
            //     refresh_model($this->attendance->getModel())
            //         ->save([
            //             'schedule_id' => $schedule->id,
            //             'time_in' => $schedule->start_event,
            //             'time_out' => $schedule->end_event,
            //             'is_leave' => 1,
            //         ]);
            // }
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
                if (isset($data['schedule_id'])) {
                    $schedule = refresh_model($this->agent_schedule->getModel())->find($data['schedule_id']);

                    if ($schedule) {
                        if (!isset($schedule->attendances)) {
                            return $this->setResponse([
                                'code' => 404,
                                'title' => "No existing attendance for this schedule.",
                            ]);
                        }

                        $data['user_id'] = $schedule->user_id;
                        $data['start_event'] = $schedule->start_event;
                        $data['end_event'] = $schedule->end_event;
                        $data['leave_type'] = 'partial_sick_leave';
                    } else {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "Schedule is not found.",
                        ]);
                    }
                } else {
                    return $this->setResponse([
                        'code' => 500,
                        'title' => "User ID is not set.",
                    ]);
                }

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
            $does_exist = $this->user->where('uid',$data['user_id'])->first();

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'User does not exist.',
                ]);
            }
        }

        if (!isset($data['id'])) {
            $does_exist = refresh_model($this->leave->getModel())
                ->where('user_id', $data['user_id'])
                ->where('start_event', '>=', $data['start_event'])
                ->where('end_event', '<=', $data['end_event'])
                ->where('leave_type', $data['leave_type'])
                ->where('status', 'approved')
                ->get()->all();

            if ($does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "{$data['leave_type']} for {$data['start_event']} to {$data['end_event']} already exists",
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

        //set auto approved
        if (isset($data['isApproved']) && $data['isApproved']) {
            $approval = $this->setLeaveApproval([
                'id' => $leave->id,
                'status' => 'approved',
                'user_access' => $leave->allowed_access,
                'approved_by' => $data['generated_by'],
            ]);
        }

        //fetch raw  agent schedules affected by the leave (query builder format)
        $raw_schedules = refresh_model($this->agent_schedule->getModel())
            ->where('user_id', $leave->user_id)
            ->where('start_event', '>=', $leave->start_event)
            ->where('end_event', '<=', $leave->end_event);

        //update agent schedules
        $raw_schedules->update([
            'leave_id' => $leave->id,
        ]);

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a leave.",
            "parameters" => [
                'leave' => $leave,
                'approval_status' => [
                    'code' => isset($approval) ? $approval->getCode() : 200,
                    'title' => isset($approval) ? $approval->getTitle() : ($leave->status != 'pending') ? "Successfully {$leave->status} leave." : 'Leave is currently pending for approval.',
                ],
            ],
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

        if (strpos($leave->leave_type, 'loa') !== false) {
            return $this->setResponse([
                "code" => 500,
                "title" => "This type of leave cannot be cancelled.",
                "parameters" => $leave,
            ]);
        }

        $data['start_leave'] = $data['cancel_event'] ?? $leave->start_event;

        if ($leave->status == 'approved') {

            //do something for partial sick leave
            if ($leave->leave_type == "partial_sick_leave") {
                //fetch available leave credits
                $leave_credits = $this->leave_credit
                    ->where('user_id', $leave->user_id)
                    ->where('leave_type', 'sick_leave')
                    ->first();

                if (!$leave_credits) {
                    $leave_credits = refresh_model($this->leave_credit->getModel());
                    $leave_credits->save([
                        'user_id' => $leave->user_id,
                        'leave_type' => 'sick_leave',
                        'value' => 0,
                    ]);
                }

                //initialize credits needed
                $credits_needed = 0;

                //fetch all schedules hit by leave
                $schedules_hit = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $leave->start_event)
                    ->where('end_event', '<=', $leave->end_event)
                    ->get()->all();

                /**
                 * loop through all schedules hit
                 * to calculate total of hours needed
                 */
                foreach ($schedules_hit as $schedule) {
                    $attendance = refresh_model($this->attendance->getModel())
                        ->where('schedule_id', $schedule->id)
                        ->first();

                    if ($attendance) {
                        $diff_mins = Carbon::parse($attendance->time_out)->diffInMinutes(Carbon::parse($attendance->raw_time_out), true);
                        $hours = $diff_mins / 60;

                        //total hours summation
                        $credits_needed += $hours;

                        $attendance->update([
                            'time_out' => Carbon::parse($attendance->raw_time_out),
                        ]);

                        //update leave credits
                        $leave_credits->update([
                            'value' => $leave_credits->value + $credits_needed,
                        ]);
                    }
                }

            }

            //raw schedules (query builder format)
            $schedules = $this->agent_schedule
                ->where('leave_id', $leave->id)
                ->where('start_event', '>=', $data['start_leave'])
                ->where('end_event', '<=', $leave->end_event);

            //remove attendance
            // foreach ($schedules->get()->all() as $schedule) {
            //     refresh_model($this->attendance->getModel())
            //         ->where('schedule_id', $schedule->id)
            //         ->where('is_leave', '!=', 0)
            //         ->delete();
            // }

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

                //initialize credits needed
                $credits_needed = 0;

                /**
                 * loop through all schedules hit
                 * to calculate total of hours needed
                 */
                $schedules_hit = refresh_model($this->agent_schedule->getModel())
                    ->where('user_id', $leave->user_id)
                    ->where('start_event', '>=', $data['start_leave'])
                    ->where('end_event', '<=', $leave->end_event)
                    ->get()->all();

                foreach ($schedules_hit as $schedule) {
                    $diff = strtotime($schedule->end_event) - strtotime($schedule->start_event);
                    $hours = $diff / (3600);

                    //total hours summation
                    $credits_needed += $hours;

                    // delete attendance
                    $attendance = $this->attendance->where("schedule_id", $schedule->id)->get()->all();
                    foreach ($attendance as $datum) {
                        $this->attendance->find($datum->id);
                        $this->attendance->delete();
                    }

                    // return leave slot
                    $leave_slot = $this->leave_slot
                        ->where('user_id', $leave->om_id)
                        ->where('date', $schedule->start_event)
                        ->where('leave_type', $leave->leave_type)
                        ->first();

                    if (isset($leave_slot)) {
                        $leave_slot->update([
                            'value' => ++$leave_slot->value,
                        ]);
                    }
                }

                //update leave credits
                $leave_credits->update([
                    'value' => $leave_credits->value + $credits_needed,
                ]);
            }
        }
        $cancel_event = new DateTime($data['cancel_event'] ?? $leave->start_event);
        $cancel_event = $cancel_event->setTime(00, 00, 00);
        $cancel_event = $cancel_event->format('Y-m-d H:i:s');
        if ($cancel_event == $leave->start_event) {
            return $this->defineLeave([
                'id' => $data['id'],
                'status' => 'cancelled',
            ]);
        } else {
            $new_end = new DateTime($data['cancel_event']);
            $new_end = $new_end->modify('-1 day')->format('Y-m-d');
            return $this->defineLeave([
                'id' => $data['id'],
                'status' => 'approved',
                'end_event' => $new_end . " 23:59:59",
            ]);
        }
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
        $data['relations'][] = 'approved_by';
        $data['relations'][] = 'leave_credits';

        //fetch user if set
        if (isset($data['user_id']) && is_numeric($data['user_id'])) {
            $data['where'][] = [
                "target" => "user_id",
                "operator" => "=",
                "value" => $data['user_id'],
            ];
        }

        //if generated_by is set
        if (isset($data['generated_by']) && is_numeric($data['generated_by'])) {
            $data['where'][] = [
                "target" => 'generated_by',
                "operator" => "=",
                "value" => $data['generated_by'],
            ];
        }

        //fetch allowed access if set
        if (isset($data['allowed_access']) && is_numeric($data['allowed_access'])) {
            $data['where'][] = [
                "target" => "allowed_access",
                "operator" => "=",
                "value" => $data['allowed_access'],
            ];
        }

        //filter by date range
        if (isset($data['start_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => ">=",
                "value" => $data['start_date'],
            ];
        }

        if (isset($data['end_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => "<=",
                "value" => $data['end_date'],
            ];
        }

        //filter by created-at range
        if (isset($data['created_at_start'])) {
            // $created_at_start = new DateTime($data['created_at_start']);
            $data['where'][] = [
                "target" => "created_at",
                "operator" => ">=",
                // "value" => $created_at_start->format('Y-m-d H:i:s')."",
                "value" => $data['created_at_start'],
            ];
        }

        if (isset($data['created_at_end'])) {
            // $created_at_end = new DateTime($data['created_at_end']);
            $data['where'][] = [
                "target" => "created_at",
                "operator" => "<=",
                // "value" => $created_at_end->format('Y-m-d H:i:s')."",
                "value" => $data['created_at_end'],

            ];
        }

        //filter by tl id
        if (isset($data['tl_id'])) {
            $data['wherehas'][] = [
                'relation' => 'schedule',
                'target' => [
                    [
                        'column' => 'tl_id',
                        'value' => $data['tl_id'],
                    ],
                ],
            ];
        }

        //filter by om id
        if (isset($data['om_id'])) {
            $data['wherehas'][] = [
                'relation' => 'schedule',
                'target' => [
                    [
                        'column' => 'om_id',
                        'value' => $data['om_id'],
                    ],
                ],
            ];
        }
        /**
         * Set access level filter
         * (to be reworked)
         */
        // if ($data['user_access'] == 15) {
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => ">=",
        //         "value" => 15,
        //     ];
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => "<=",
        //         "value" => 17,
        //     ];
        // } else if ($data['user_access'] >= 12 && $data['user_access'] <= 14) {
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => ">=",
        //         "value" => 12,
        //     ];
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => "<=",
        //         "value" => 14,
        //     ];
        // } else if ($data['user_access'] <= 3) {
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => ">",
        //         "value" => 0,
        //     ];
        // } else {
        //     $data['where'][] = [
        //         "target" => "allowed_access",
        //         "operator" => "=",
        //         "value" => $data['user_access'],
        //     ];
        // }

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

        if (!isset($data['single'])) {
            foreach ($result as $key => $value) {
                $result[$key]->append('recently_approved');
            }
        } else {
            $result->append('recently_approved');
        }

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
        $data['relations'][] = 'leave_credits';

        //filter by date range
        if (isset($data['start_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => ">=",
                "value" => $data['start_date'],
            ];
        }

        if (isset($data['end_date'])) {
            $data['where'][] = [
                "target" => "start_event",
                "operator" => "<=",
                "value" => $data['end_date'],
            ];
        }

        //filter by tl id
        if (isset($data['tl_id'])) {
            $data['wherehas'][] = [
                'relation' => 'schedule',
                'target' => [
                    [
                        'column' => 'tl_id',
                        'value' => $data['tl_id'],
                    ],
                ],
            ];
        }

        //filter by om id
        if (isset($data['om_id'])) {
            $data['wherehas'][] = [
                'relation' => 'schedule',
                'target' => [
                    [
                        'column' => 'om_id',
                        'value' => $data['om_id'],
                    ],
                ],
            ];
        }

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