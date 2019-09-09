<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data\Repositories\VoluntaryTimeOutRepository;
use App\Http\Controllers\BaseController;

class VoluntaryTimeOutController extends BaseController
{
    protected $vto_repo;

    public function __construct(
        VoluntaryTimeOutRepository $vtoRepository
    ) {
        $this->vto_repo = $vtoRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();

        // if (isset($data['search']) || isset($data['target'])) {
        //     return $this->absorb($this->agent_schedule_repo->searchAgentSchedule($data))->json();
        // } else {
            return $this->absorb($this->vto_repo->fetchVto($data))->json();
        // }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $option = null)
    {
        return $this->absorb($this->vto_repo->defineVto($request->all(), $option))->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->vto_repo->searchVto($data))->json();
    }
}
