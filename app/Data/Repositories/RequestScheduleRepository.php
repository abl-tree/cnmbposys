<?php
namespace App\Data\Repositories;

use App\Data\Models\RequestSchedule;
use App\Data\Models\EventTitle;
use App\User;
use App\Data\Repositories\BaseRepository;

class RequestScheduleRepository extends BaseRepository
{

    protected 
        $request_schedule,
        $user,
        $event_title;

    public function __construct(
        RequestSchedule $requestSchedule,
        User $user,
        EventTitle $eventTitle
    ) {
        $this->request_schedule = $requestSchedule;
        $this->user = $user;
        $this->event_title = $eventTitle;
    }

    public function defineRequestSchedule($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['applicant'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Applicant is not set.",
                ]);
            }

            if (!isset($data['start_date'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Start date is not set.",
                ]);
            }

            if (!isset($data['end_date'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "End date is not set.",
                ]);
            }

            if (!isset($data['title_id'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Title ID is not set.",
                ]);
            }

            if (!isset($data['requested_by'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Requester is not set.",
                ]);
            }

            if (!isset($data['managed_by'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Manager is not set.",
                ]);
            }

        }
        // end data validation

        // existence check

        if (isset($data['id'])) {
            $does_exist = $this->request_schedule->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Request Schedule does not exist.',
                ]);
            }
        }

        if (isset($data['title_id'])) {
            $title_id = $this->event_title->find($data['title_id']);

            if (!$title_id) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Event title does not exist.',
                ]);
            }
        }

        if (isset($data['applicant'])) {
            $applicant = $this->user->find($data['applicant']);

            if (!$applicant) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Applicant does not exist.',
                ]);
            }
        }

        if (isset($data['requested_by'])) {
            $requested_by = $this->user->find($data['requested_by']);

            if (!$requested_by) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Requester does not exist.',
                ]);
            }
        }

        if (isset($data['managed_by'])) {
            $managed_by = $this->user->find($data['managed_by']);

            if (!$managed_by) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Manager does not exist.',
                ]);
            }
        }

        if($data['start_date'] > $data['end_date']){
            return $this->setResponse([
                'code'  => 500,
                'title' => 'Invalid date range.',
            ]);
        }

        // end existence check

        // insertion

        if (isset($data['id'])) {
            $request_schedule = $this->request_schedule->find($data['id']);
        } else {
            $request_schedule = $this->request_schedule->init($this->request_schedule->pullFillable($data));
        }

        if (!$request_schedule->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $request_schedule->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an request schedule.",
            "parameters" => $request_schedule,
        ]);

        // end insertion

    }

    public function deleteRequestSchedule($data = [])
    {
        $record = $this->request_schedule->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Request schedule not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting request schedule was not successful.",
                "meta"    => [
                    "errors" => $record->errors(),
                ],
                "parameters" => [
                    'schedule_id' => $data['id']
                ]
            ]);
        }

        return $this->setResponse([
            "code"        => 200,
            "title"       => "Request schedule deleted",
            "description" => "An request schedule was deleted.",
            "parameters"  => $record
        ]);

    }

    public function fetchRequestSchedule($data = [])
    {
        $meta_index = "request_schedules";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "request_schedule";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['request_schedule_id'] = $data['id'];

        }

        $data['relations'] = [
            'applicant.info',
            'requested_by.info',
            'managed_by.info',
            'title'
        ];

        $count_data = $data;

        $result     = $this->fetchGeneric($data, $this->request_schedule);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No request schedules are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->request_schedule->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved request schedules",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function searchRequestSchedule($data)
    {
        if(!isset($data['query'])){
            return $this->setResponse([
                "code"       => 500,
                "title"      => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->request_schedule;

        $meta_index = "request_schedules";
        $parameters = [
            "query" => $data['query'],
        ];
        
        $data['relations'] = [
            'applicant.info',
            'requested_by.info',
            'managed_by.info',
            'title'
        ];


        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No request schedules are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
        

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->request_schedule->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched request schedules",
            "meta" => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
