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
        $this->middleware('loginVerif');
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
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);

        $userInfo = AccessLevel::all();
        return view('admin.dashboard.profile', compact('profile', 'role', 'user', 'userInfo'));
    }

    public function refreshEmployeeList(){//Used when loading default datatable and in showAll f or Admin/HRs
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;

        if(isAdminHRM()){
            $emp = AccessLevelHierarchy::with('childInfo.user.access')->get();
            $employeeList = $emp->where('childInfo.user.access_id', '>', $access_level)->where('childInfo.status', '<>', 'Terminated');
        }
        else{
            $emp = AccessLevelHierarchy::with('childInfo.user.access')->where('parent_id', $id)->get();
            $employeeList = $emp->where('childInfo.status', '<>', 'Terminated');
        }

        return $this->reloadDatatable($employeeList);
    }

    public function childView(){
        $id = auth()->user()->id;
        $employeeList = AccessLevelHierarchy::with('childInfo.user.access')->where('parent_id', $id)->get();
        return $this->reloadDatatable($employeeList);
    }

    public function terminatedView(){
        $employeeList = UserInfo::with('user.access')->where('status', 'Terminated')->get();

        return Datatables::of($employeeList)
        ->addColumn('employee_status', function($data){
            if(isAdminHRM()){
                if($data->status=='Terminated'){
                    return '<button class=" btn btn-sm btn-danger update_status" id="'.$data->id.'">TERMINATED</button>';
                }else if($data->status=='Active'){
                    return '<button class="btn btn-sm  btn-success update_status" id="'.$data->id.'">ACTIVE</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning update_status" id="'.$data->id.'">ACTIVE</button>';
                }
            }
            else{
                if($data->status=='Terminated'){
                    return '<button class=" btn btn-sm btn-danger" disabled>TERMINATED</button>';
                }else if($data->status=='Active'){
                    return '<button class="btn btn-sm  btn-success" disabled>ACTIVE</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning" disabled>ACTIVE</button>';
                }
            }
        })
        ->addColumn('action', function($employeeList){
            return '<h6>No Valid Action</h6>';
        })
        ->editColumn('name', function ($data){
            return $data->firstname." ".$data->middlename." ".$data->lastname;
        })
        ->rawColumns(['employee_status', 'action'])
        ->make(true);
    }

    public function reloadDatatable($employeeList){
        return Datatables::of($employeeList)
        ->addColumn('employee_status', function($data){
            if(isAdminHRM()){
                if($data->childInfo->status=='Terminated'){
                    return '<button class=" btn btn-sm btn-danger update_status" id="'.$data->child_id.'">TERMINATED</button>';
                }else if($data->childInfo->status=='Active'){
                    return '<button class="btn btn-sm  btn-success update_status" id="'.$data->child_id.'">ACTIVE</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning update_status" id="'.$data->child_id.'">ACTIVE</button>';
                }
            }
            else{
                if($data->childInfo->status=='Terminated'){
                    return '<button class=" btn btn-sm btn-danger" disabled>TERMINATED</button>';
                }else if($data->childInfo->status=='Active'){
                    return '<button class="btn btn-sm  btn-success" disabled>ACTIVE</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning" disabled>ACTIVE</button>';
                }
            }
        })
        ->addColumn('action', function($employeeList){
            if($employeeList->childInfo->status == 'Terminated'){
                return '<h6>No Valid Action</h6>';
            }

            if(canIR() && isAdminHR()){
                return '<div class="btn-group" role="group" aria-label="Third group">
                <button class="btn btn-xs btn-info ti-eye view-employee" id="'.$employeeList->child_id.'"></button>
                <button class="btn btn-xs btn-secondary ti-pencil-alt2 form-action-button" data-portion="table" data-action="edit" data-id="'.$employeeList->child_id.'"></button>
                <button class="btn btn-xs btn-danger ti-plus add_nod" id="'.$employeeList->child_id.'"></button>
                </div>';
            }
            else if(canIR()){
                return '<div class="btn-group" role="group" aria-label="Third group">
                <button class="btn btn-xs btn-info ti-eye view-employee" id="'.$employeeList->child_id.'"></button>
                <button class="btn btn-xs btn-danger ti-plus add_nod" id="'.$employeeList->child_id.'"></button>
                </div>';
            }
            else{
                return '<div class="btn-group" role="group" aria-label="Third group">
                <button class="btn btn-xs btn-info ti-eye view-employee" id="'.$employeeList->child_id.'"></button>
                </div>';
            }
        })
        ->editColumn('name', function ($data){
            return $data->childInfo->firstname." ".$data->childInfo->middlename." ".$data->childInfo->lastname;
        })
        ->rawColumns(['employee_status', 'action'])
        ->make(true);
    }

    public function viewProfile(Request $request){
        $id = $request->get('id');
        $user = User::find($id);
        $access_level = $user->access_id;
        $role = AccessLevel::find($access_level);
        $user = User::find($id);
        $profile = UserInfo::with('benefits')->find($id);

        $output = array(
            'profile' => $profile,
            'role' => $role,
            'user' => $user
        );
        echo json_encode($output);
    }

    public function backToProfile(){
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);

        $userInfo = AccessLevel::all();

        $output = array(
            'profile' => $profile,
            'role' => $role,
            'user' => $user,
        );
        echo json_encode($output);
    }
    public function checkLoginStat(){
        $users = User::find(auth()->user()->id);
        $output = array(
            'flagerino' => $users->loginFlag,
        );
        echo json_encode($output);
    }
    

    public function updateEmployeeList(Request $request){
        $id = $request->get('id');
        
        $employeeList = AccessLevelHierarchy::with('childInfo.user.access')->where('parent_id', $id)->get();
        return $this->reloadDatatable($employeeList);
    }

    public function getCurrentProfile(){
        $id = auth()->user()->id;

        return $id;
    }

    public function getCurrentTab(){
        //Default Tab will depend on user's position
        //Only the Admin and HRM can have access to show all tab
        if(isAdminHRM()){
            return 'showAll';
        }
        else{
            return 'childView';
        }
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
