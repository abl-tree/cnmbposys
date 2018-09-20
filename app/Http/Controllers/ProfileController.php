<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserBenefit;
use App\AccessLevel;
use App\UserInfo;
use App\User;
use Yajra\Datatables\Datatables;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $id = auth()->user()->id;
        $user = auth()->user()->access_id;
        $hierarchy = AccessLevel::find($user);
        $profile = UserBenefit::with('info', 'benefit')->where('user_info_id', $id)->get();

        return view('admin.dashboard.profile', compact('profile', 'hierarchy'));
    }

    public function refreshEmployeeList(){
        $employeeList = UserInfo::all();
        return Datatables::of($employeeList)
        ->editColumn('name', function ($data){
            return $data->firstname." ".$data->middlename." ".$data->lastname;
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
