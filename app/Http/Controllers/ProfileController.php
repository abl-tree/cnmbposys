<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Data\Models\UserBenefit;
use App\Data\Models\AccessLevel;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\UserInfo;
use App\User;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;

class ProfileController extends BaseController
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
        $emp = AccessLevelHierarchy::with('childInfo.user.access')->orderBy('parent_id')->get();

        $userInfo = AccessLevel::all();

        if($access_level>3){
            $underconstruction = "/images/underconstruction.png";
            return view('admin.dashboard.underconstruction', compact('profile', 'role', 'user', 'userInfo', 'emp','underconstruction'));
        }else{
            return view('admin.dashboard.profile', compact('profile', 'role', 'user', 'userInfo', 'emp'));
        }
    }

    public function refreshEmployeeList(){//Used when loading default datatable and in showAll f or Admin/HRs
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;

        if(isAdminHRM()){
            $emp = AccessLevelHierarchy::with('childInfo.user.access')->orderBy('parent_id', 'asc')->get();
            $employeeList = $emp->where('childInfo.user.access_id', '>', $access_level)->where('childInfo.status', '<>', 'inactive');
        }
        else{
            $emp = AccessLevelHierarchy::with('childInfo.user.access')->where('parent_id', $id)->get();
            $employeeList = $emp->where('childInfo.status', '<>', 'inactive');
        }

        return $this->reloadDatatable($employeeList);
    }

    public function childView(){
        $id = auth()->user()->id;
        $employeeList = AccessLevelHierarchy::with('childInfo.user.access')->where('parent_id', $id)->get();
        return $this->reloadDatatable($employeeList);
    }

    public function terminatedView(){
        $employeeList = UserInfo::with('user.access')->where('status', 'inactive')->get();

        return Datatables::of($employeeList)
        ->addColumn('employee_status', function($data){
            if(isAdminHRM()){
                if($data->status=='inactive'){
                    return '<button class=" btn btn-sm btn-danger update_status" id="'.$data->id.'">Inactive</button>';
                }else if($data->status=='active'){
                    return '<button class="btn btn-sm  btn-primary update_status" id="'.$data->id.'">Active</button>';
                }else if($data->status=='new_hired'){
                    return '<button class="btn btn-sm  btn-success update_status" id="'.$data->id.'">Newly Hired</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning update_status" id="'.$data->id.'">No Status</button>';
                }
            }
            else{
                if($data->status=='inactive'){
                    return '<button class=" btn btn-sm btn-danger" disabled>Inactive</button>';
                }else if($data->status=='active'){
                    return '<button class="btn btn-sm  btn-primary" disabled>Active</button>';
                }else if($data->status=='new_hired'){
                    return '<button class="btn btn-sm  btn-success" disabled>Newly Hired</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning" disabled>No Status</button>';
                }
            }
        })
        ->addColumn('action', function($employeeList){
            return '<h6>No Valid Action</h6>';
        })
        ->editColumn('name', function ($data){
            return $data->firstname." ".$data->middlename." ".$data->lastname;
        })
        ->editColumn('company_id', function ($data){       
                return $data->user->company_id;
        })
        ->rawColumns(['employee_status', 'action'])
        ->make(true);
    }

    public function reloadDatatable($employeeList){
        return Datatables::of($employeeList)
        ->addColumn('employee_status', function($data){
            if(isAdminHRM()){
                if($data->childInfo->status=='inactive'){
                    return '<button class=" btn btn-sm btn-danger update_status" id="'.$data->child_id.'">Inactive</button>';
                }else if($data->childInfo->status=='active'){
                    return '<button class="btn btn-sm  btn-primary update_status" id="'.$data->child_id.'">Active</button>';
                }else if($data->childInfo->status=='new_hired'){
                    return '<button class="btn btn-sm  btn-success update_status" id="'.$data->child_id.'">Newly Hired</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning" disabled>No Status</button>';
                }
            }
            else{
                if($data->childInfo->status=='inactive'){
                    return '<button class=" btn btn-sm btn-danger" disabled>Inactive</button>';
                }else if($data->childInfo->status=='active'){
                    return '<button class="btn btn-sm  btn-primary" disabled>Active</button>';
                }else if($data->childInfo->status=='new_hired'){
                    return '<button class="btn btn-sm  btn-success" disabled>Newly Hired</button>';
                }else{
                    return '<button class="btn btn-sm  btn-warning" disabled>No Status</button>';
                }
            }
        })
        ->addColumn('action', function($employeeList){
            if($employeeList->childInfo->status == 'inactive'){
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
        ->editColumn('company_id', function ($data){
            if($data->parent_id){
                return $data->childInfo->user->company_id;
            }else{
                return "<span class='badge badge-danger'>NA</span><span style='color:black;'> ".$data->childInfo->user->company_id."</span>";
            }
        })
        ->rawColumns(['employee_status', 'action', 'company_id'])
        ->make(true);
    }

    public function viewProfile(Request $request){
        $id = $request->get('id');
        $user = User::find($id);
        $access_level = $user->access_id;
        $role = AccessLevel::find($access_level);
        $user = User::find($id);
        $viewer = auth()->user()->access_id;
        $profile = UserInfo::with('benefits')->find($id);

        $output = array(
            'profile' => $profile,
            'role' => $role,
            'user' => $user,
            'viewer' => $viewer
        );
        echo json_encode($output);
    }

    public function backToProfile(){
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $viewer = auth()->user()->access_id;
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);

        $userInfo = AccessLevel::all();

        $output = array(
            'profile' => $profile,
            'role' => $role,
            'user' => $user,
            'viewer' => $viewer
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
    

    public function updateEmployeeList($id, Request $request){
        
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
