<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\LogsRepository;

class LogsController extends BaseController
{
   protected $action_logs;

    public function __construct(
        LogsRepository $logsRepository
    ){
        $this->action_logs = $logsRepository;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->action_logs->logs($data))->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $log= $request->isMethod('put')? ActionLogs::findOrFail($request->id): New ActionLogs;

        $log->id = $request->input('user_id');
        $log->action = $request->input('action');
        $log->affected_data = $request->input('affected_data');

        if($log->save()){
          return $log->json();
        }
        
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
}
