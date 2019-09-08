<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\OvertimeRepository;

class OvertimeController extends BaseController
{
    protected $overtime_repo;

    public function __construct(
        OvertimeRepository $overtimeRepository
    ) {
        $this->overtime_repo = $overtimeRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->fetchOvertime($data))->json();
    }

    public function agents(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->fetchAgentOvertime($data))->json();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->fetchOvertime($data))->json();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->searchOvertimeSchedule($data))->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->defineOvertimeSchedule($data))->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->overtime_repo->defineAgentOvertime($data))->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data["id"] = $id;
        return $this->absorb($this->overtime_repo->defineOvertimeSchedule($data))->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Overtime Schedule ID is not set.",
            ]);
        }

        return $this->absorb($this->overtime_repo->deleteOvertime($data))->json();
    }

    public function approve(Request $request, $option = null)
    {
        $data = $request->all();

        return $this->absorb($this->overtime_repo->approveOvertime($data, $option))->json();
    }

    public function searchAgent(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->overtime_repo->searchAgentOvertimeSchedule($data))->json();
    }
}
