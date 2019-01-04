<?php
/**
 * Created by PhpStorm.
 * User: Janrey
 * Date: 30/10/2018
 * Time: 2:12 PM
 */

namespace App\Data\Repositories;

use App\Data\Models\EventTitle;
use App\Data\Models\UserInfo;
use App\User;
use App\Data\Repositories\BaseRepository;

class EventTitleRepository extends BaseRepository
{

    protected 
        $event_title,
        $user;

    public function __construct(
        EventTitle $eventTitle,
        User $user
    ) {
        $this->event_title = $eventTitle;
        $this->user = $user;
    }

    public function defineEventTitle($data = [])
    {
        // data validation
        if (!isset($data['id'])) {

            if (!isset($data['title'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Title is not set.",
                ]);
            }

            if (!isset($data['color'])) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => "Color is not set.",
                ]);
            }

        }
        // data validation

        // existence check

        if (isset($data['id'])) {
            $does_exist = $this->event_title->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Event Title does not exist.',
                ]);
            }
        }

        // existence check

        // insertion

        if (isset($data['id'])) {
            $event_title = $this->event_title->find($data['id']);
        } else {
            $event_title = $this->event_title->init($this->event_title->pullFillable($data));
        }

        if (!$event_title->save($data)) {
            return $this->setResponse([
                "code"        => 500,
                "title"       => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta"        => [
                    "errors" => $event_title->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully defined an event title.",
            "parameters" => $event_title,
        ]);

        // insertion

    }

    public function deleteEventTitle($data = [])
    {
        $record = $this->event_title->find($data['id']);

        if (!$record) {
            return $this->setResponse([
                "code"        => 404,
                "title"       => "Event title not found"
            ]);
        }

        if (!$record->delete()) {
            return $this->setResponse([
                "code"    => 500,
                "message" => "Deleting event title was not successful.",
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
            "title"       => "Event title deleted",
            "description" => "An event title was deleted.",
            "parameters"        => [
                "schedule_id" => $data['id']
            ]
        ]);

    }

    public function fetchEventTitle($data = [])
    {
        $meta_index = "event_titles";
        $parameters = [];
        $count      = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index     = "event_title";
            $data['single'] = true;
            $data['where']  = [
                [
                    "target"   => "id",
                    "operator" => "=",
                    "value"    => $data['id'],
                ],
            ];

            $parameters['event_title_id'] = $data['id'];

        }

        $count_data = $data;

        // $data['relations'] = "user_info";

        $result     = $this->fetchGeneric($data, $this->event_title);

        if (!$result) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No event titles are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->event_title->getModel()));

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved event titles",
            "meta"       => [
                $meta_index => $result,
                "count"     => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

}
