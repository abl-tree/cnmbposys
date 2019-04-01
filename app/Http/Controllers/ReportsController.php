<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\ReportsRepository;
use App\Data\Models\UserReport;


class ReportsController extends BaseController
{
     protected $user_reports;

    public function __construct(
        ReportsRepository $ReportsRepository
    ){
        $this->user_reports = $ReportsRepository;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getAllReports($data))->json();
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->ReportsInputCheck($data))->json();     
    }

     public function addSanctionType(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->addSanctionType($data))->json();     
    }

     public function addSanctionLevel(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->addSanctionLevel($data))->json();     
    }
     public function userResponse(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->userResponse($data))->json();     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, $id)
    {
        $data['id'] = $id;
        
        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->user_reports->fetchUserReport($data))->json();
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
}
