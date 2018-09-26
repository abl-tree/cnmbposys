<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\UserBenefit;
use App\AccessLevel;
use App\AccessLevelHierarchy;
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
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserBenefit::with('info', 'benefit')->where('user_info_id', $id)->get();

        $userInfo = AccessLevel::all();
        return view('admin.dashboard.profile', compact('profile', 'role','userInfo'));
    }

    public function refreshEmployeeList(){
        $id = auth()->user()->id;

        $employeeList = AccessLevelHierarchy::with('childInfo')->where('parent_id', $id)->get();
        return Datatables::of($employeeList)
         ->addColumn('employee_status', function($data){
            if($data->childInfo->status=='Terminated'){
                return '<button class="btn-sm btn-danger update_status" id="'.$data->child_id.'">TERMINATED</button>';
            }else if($data->childInfo->status=='Active'){
                return '<button class="btn-sm btn-success update_status" id="'.$data->child_id.'">ACTIVE</button>';
            }else{
                return 'AMBOT';
            }
        })
        ->addColumn('action', function($employeeList){
            return '<button class="btn btn-xs btn-secondary ti-pencil-alt2 form-action-button" data-url="/employee/'.$employeeList->child_id.'" data-action="edit" data-id="'.$employeeList->child_id.'"></button>
            <button class="btn btn-xs btn-info ti-eye view-employee" id="'.$employeeList->child_id.'"></button>&nbsp<button class="btn btn-xs btn-danger ti-plus add_nod" id="'.$employeeList->child_id.'"></button>';
        })

        ->editColumn('name', function ($data){
            return $data->childInfo->firstname." ".$data->childInfo->middlename." ".$data->childInfo->lastname;
        })
        ->rawColumns(['employee_status', 'action'])
        ->make(true);
    }

    public function refreshEmployeeDatatable(){
        
    }

    public function viewProfile(Request $request){
        $id = $request->get('id');
        $user = User::find($id);
        $access_level = $user->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserBenefit::with('info', 'benefit')->where('user_info_id', $id)->get();

        $output = array(
            'profile' => $profile,
            'role' => $role
        );
        echo json_encode($output);

    }

    public function updateEmployeeList(Request $request){
        $id = $request->get('id');
        
        $employeeList = AccessLevelHierarchy::with('childInfo')->where('parent_id', $id)->get();
        return Datatables::of($employeeList)
         ->addColumn('employee_status', function($data){
            if($data->childInfo->status=='Terminated'){
                return '<button class="btn-sm btn-danger update_status" id="'.$data->child_id.'">TERMINATED</button>';
            }else if($data->childInfo->status=='Active'){
                return '<button class="btn-sm btn-success update_status" id="'.$data->child_id.'">ACTIVE</button>';
            }else{
                return 'AMBOT';
            }
        })
        ->addColumn('action', function($employeeList){
            return '<button class="btn btn-xs btn-secondary ti-pencil-alt2" id="'.$employeeList->child_id.'"></button>
            <button class="btn btn-xs btn-info ti-eye view-employee" id="'.$employeeList->child_id.'"></button>';
        })
        ->editColumn('name', function ($data){
            return $data->childInfo->firstname." ".$data->childInfo->middlename." ".$data->childInfo->lastname;
        })
        ->rawColumns(['employee_status', 'action'])
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
