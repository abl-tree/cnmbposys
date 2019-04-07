<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\EventTitleRepository;

class EventTitleController extends BaseController
{
    protected $event_title_repo;

    public function __construct(
        EventTitleRepository $eventTitleRepository
    ){
        $this->event_title_repo = $eventTitleRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->event_title_repo->fetchEventTitle($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->event_title_repo->defineEventTitle($data))->json();
    }

    public function delete(Request $request, $id)
    {
        $data       = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "Event title ID is not set.",
            ]);
        }

        return $this->absorb($this->event_title_repo->deleteEventTitle($data))->json();
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
                'title' => "Event title ID is invalid.",
            ]);
        }

        return $this->absorb($this->event_title_repo->fetchEventTitle($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->event_title_repo->defineEventTitle($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->event_title_repo->searchEventTitle($data))->json();
    }

    public function select(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->event_title_repo->fetchEventTitleSelect($data))->json();
        dd($response);
    }


}
