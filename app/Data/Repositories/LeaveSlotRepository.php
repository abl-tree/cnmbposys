<?php

namespace App\Data\Repositories;

use App\Data\Models\LeaveSlot;
use App\Data\Repositories\BaseRepository;
use App\User;

class LeaveSlotRepository extends BaseRepository
{

    protected $leave_slot,
        $user;

    public function __construct(
        LeaveSlot $leaveSlot,
        User $user
    ) {
        $this->leave_slot = $leaveSlot;
        $this->user = $user;
    }

    public function defineLeaveSlot($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['user_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "User ID is not set.",
                ]);
            }

            if (!isset($data['leave_type'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Leave type is not set.",
                ]);
            }

            if (!isset($data['value'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Value is not set.",
                ]);
            }

            if (!isset($data['original_value'])) {
                $data['original_value'] = $data['value'];
            }

            if (!isset($data['date'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Date is not set.",
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['id'])) {
            $does_exist = $this->leave_slot->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'Leave slot does not exist.',
                ]);
            }
            // original value - remaining slots = consumed slots
            // consumed slots > data throw error
            if($does_exist->original_value != $does_exist->value){
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'Slot not shrinkable, new value must be equal or greater than the consumed slots.',
                ]);
            }
            $data['original_value'] = $data['value'];
            $data['value'] = $data['value'];
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


        if (!isset($data['id'])) {

            $does_exist = $this->leave_slot
                ->where('user_id', $data['user_id'])
                ->where('leave_type', $data['leave_type'])
                ->where('date', $data['date'])
                ->first();

            if ($does_exist) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Leave slot for this user's {$data['leave_type']}  for {$data['date']} already exists.",
                    "parameters" => $data,
                ]);
            }
        }

        // $does_exist = $this->leave_slot
        //     ->where('user_id', $data['user_id'])
        //     ->where('date', $data['date'])
        //     ->first();

        // existence check

        // insertion

        if (isset($data['id'])) {
            $leave_slot = $this->leave_slot->find($data['id']);
        } else if ($does_exist) {
            $leave_slot = $does_exist;
        } else {
            $leave_slot = $this->leave_slot->init($this->leave_slot->pullFillable($data));
        }

        if (!$leave_slot->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $leave_slot->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a leave slot.",
            "parameters" => $leave_slot,
        ]);

        // insertion

    }

    public function deleteLeaveSlot($data = [])
    {
        $leave_slot = $this->leave_slot->find($data['id']);

        if (!$leave_slot) {
            return $this->setResponse([
                "code" => 404,
                "title" => "Leave slot not found",
            ]);
        }

        if($leave_slot->original_value != $leave_slot->value){
            return $this->setResponse([
                "code" => 500,
                "title" => "Slots cannot be deleted.",
            ]);
        }

        if (!$leave_slot->delete()) {
            return $this->setResponse([
                "code" => 500,
                "message" => "Deleting leave slot was not successful.",
                "meta" => [
                    "errors" => $leave_slot->errors(),
                ],
                "parameters" => [
                    'title_id' => $data['id'],
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Leave slot deleted",
            "description" => "A leave slot was deleted.",
            "parameters" => $leave_slot,
        ]);

    }

    public function fetchLeaveSlot($data = [])
    {
        $meta_index = "leave_slots";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "leave_slot";
            $data['single'] = true;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['leave_slot_id'] = $data['id'];

        }

        //fetch user if set
        if (isset($data['user_id']) && is_numeric($data['user_id'])) {
            $data['where'][] = [
                "target" => "user_id",
                "operator" => "=",
                "value" => $data['user_id'],
            ];
        }

        //fetch per leave_type
        if (isset($data['leave_type'])) {
            $data['where'][] = [
                "target" => "leave_type",
                "operator" => "=",
                "value" => $data['leave_type'],
            ];
        }

        //fetch per date range
        if (isset($data['start_event'])) {
            $data['where'][] = [
                "target" => "date",
                "operator" => ">=",
                "value" => $data['start_event'],
            ];
        }

        if (isset($data['end_event'])) {
            $data['where'][] = [
                "target" => "date",
                "operator" => "<=",
                "value" => $data['end_event'],
            ];
        }

        //relations
        $data['relations'][] = 'user';

        //fetch user if set
        if (isset($data['user_id']) && is_numeric($data['user_id'])) {
            $data['where'][] = [
                "target" => "user_id",
                "operator" => "=",
                "value" => $data['user_id'],
            ];
        }

        $count_data = $data;

        $result = $this->fetchGeneric($data, $this->leave_slot);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No leave slots are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->leave_slot->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved leave slots",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function countLeaveSlots($data)
    {
        if (!isset($data['start_event'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Start event is not set",
                "parameters" => $data,
            ]);
        }

        if (!isset($data['end_event'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "End event is not set",
                "parameters" => $data,
            ]);
        }

        $count = $this->leave_slot
            ->where('date', '>=', $data['start_event'])
            ->where('date', '<=', $data['end_event'])
            ->sum('value');

        if (!$count || $count <= 0) {
            return $this->setResponse([
                "code" => 404,
                "title" => "No leave slots were found",
                "meta" => [
                    "count" => 0,
                ],
                "parameters" => $data,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved leave slots count",
            "meta" => [
                "count" => $count,
            ],
            "parameters" => $data,
        ]);
    }

    public function searchLeaveSlot($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->leave_slot;

        $meta_index = "leave_slots";
        $parameters = [
            "query" => $data['query'],
        ];

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No leave slots are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->leave_slot->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched leave slots",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}