<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Data\Models\UserInfo;
use App\Data\Models\UserBenefit;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\AccessLevel;
use App\Data\Models\ExcelTemplateValidator;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;


class EmployeeController extends BaseController
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.form.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user="";
        $userinfo="";
        $access_level_hierarchy="";
        $email="";
        $pemail="";
        $fullname_hash = str_replace(' ', '', strtolower($request->first_name.$request->middle_name.$request->last_name));
        $excel_hash = UserInfo::all()->pluck('excel_hash')->toArray();
        $admin_designation = "required";
        $role = $request->role;

        if($role==1){
            $admin_designation="";
        }

        if($request->action=='add'){
            $userinfo = new UserInfo;
            $user = new User;
            $access_level_hierarchy = new AccessLevelHierarchy;
            $email = 'required|unique:users|email';
            //check if fullname exist
            if(in_array($fullname_hash,$excel_hash)){
                return response()->json(['errors'=>['first_name'=>'Name Already Exist.','middle_name'=>'Name Already Exist.','last_name'=>'Name Already Exist.']]);
            }
        }else if($request->action=='edit'){
            $userinfo = UserInfo::find($request->id);
            $user = User::find($request->id);
            $access_level_hierarchy = AccessLevelHierarchy::where('child_id','=',$request->id)->first();
            if($user->email == $request->email){
                $email = 'required|email';
            }else{
                $email = 'required|unique:users|email';
            }
            if($userinfo->p_email == $request->p_email){
                $pemail = 'required|email';
            }else{
                $pemail = 'required|unique:user_infos|email';
            }
            if($userinfo->excel_hash != $fullname_hash){
                if(in_array($fullname_hash,$excel_hash)){
                    return response()->json(['errors'=>['first_name'=>'Name Already Exist.','middle_name'=>'Name Already Exist.','last_name'=>'Name Already Exist.']]);
                }
            }
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'company_id' => 'required',
            // 'p_email' => $pemail,
            // 'contact' => 'required',
            'email' => $email,
            'position' => 'required',
            // 'salary' => 'required',
            'designation'=>$admin_designation,
            'hired_date'=>'required',
            'photo'=>'image|max:2000',
        ]);


        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $userinfo->firstname=$request->first_name;
        $userinfo->lastname=$request->last_name;
        $userinfo->middlename=$request->middle_name;
        $userinfo->address=$request->address;
        $userinfo->birthdate=$request->birthdate;
        $userinfo->gender=$request->gender;
        $userinfo->salary_rate=$request->salary;
        $userinfo->contact_number=$request->contact;
        $userinfo->hired_date=$request->hired_date;
        if($request->action == "add"){
            $userinfo->status="new_hired";
        }
        $userinfo->excel_hash = $fullname_hash;
        $userinfo->p_email = $request->p_email;
        if($request->hasFile('photo')){
            $binaryfile = file_get_contents($_FILES['photo']['tmp_name']);
            $userinfo->image_ext= explode(".", strtolower($_FILES['photo']['name']))[1];
            $userinfo->image = 'data:image/'.explode(".", strtolower($_FILES['photo']['name']))[1].';base64,'.base64_encode($binaryfile);
            $userinfo->save();
        }
        if($request->captured_photo){
            $userinfo->image_ext='jpg';
            $userinfo->image = $request->captured_photo;
            $userinfo->save();
        }
        $userinfo->save();

        
        $user->uid= $userinfo->id;
        $user->email = $request->email;
        if($request->action=="add"){
            $user->password = str_replace(' ', '', strtolower($userinfo->firstname.$userinfo->lastname));
        }
        $user->access_id = $request->position;
        $user->contract = $request->contract;
        $user->company_id = $request->company_id;
        $user->save();

        $obj_benefit=[];
        
        if($request->action=='add'){
            for($l=0;$l<4;$l++){
                $obj_benefit[]=['user_info_id'=>$userinfo->id,'benefit_id'=>$l+1,'id_number'=>$request->id_number[$l]];
            }
            UserBenefit::insert($obj_benefit);
            $access_level_hierarchy->child_id = $userinfo->id;
        }else if($request->action=='edit'){
            for($l=0;$l<4;$l++){
                UserBenefit::where('user_info_id',$request->id)
                ->where('benefit_id',$l+1)
                ->update(['id_number'=>$request->id_number[$l]]);
            }
            $access_level_hierarchy->child_id = $request->id;
        }
        if($request->position>1){
            $access_level_hierarchy->parent_id = $request->designation;
        }else if($request->position==1){
            $access_level_hierarchy->parent_id = null;
        }
        
        $check = $access_level_hierarchy->save();
        if($check){
            $etv = new ExcelTemplateValidator;
            $etv = $etv->updateExcelToken("Reassign");
            return response()->json(['success'=>'Record is successfully added','info'=>$userinfo,'user'=>$user,'benefit'=>UserBenefit::where('user_info_id',$request->id)->get()]);
        }
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




    //fetching designation dynamic data

    function fetch(Request $request)
    {
        $position = $request->get('applicant_position');
        $userposition = $request->get('user_position'); 
        $eid = $request->get('employee_id'); 
        $accesslevel = new AccessLevel;
        $parentLevel = $accesslevel->getParentLevel($position);
        
        // echo '<option>here'.$position.'</option>';
        $data = DB::table('users')
                    ->join('access_levels','users.access_id','=','access_levels.id')
                    ->join('user_infos','user_infos.id','=','users.uid')
                    ->select('user_infos.id','user_infos.firstname','user_infos.lastname','user_infos.middlename','access_levels.name as accesslevelname')
                    ->where([['access_levels.id','=',$parentLevel],['user_infos.status','!=','Terminated']])
                    ->get();
        $output="";
        if($data->count()>0){
            $output.= '<option value="">Select '.$data[0]->accesslevelname.'</option>';
            foreach($data as $datum){
                if($datum->id!=$eid){
                    $output .= '<option value="'.$datum->id.'">'.$datum->lastname.", ".$datum->firstname." ".$datum->middlename.'</option>';
                }
            }
        }else{
            $val = "";
            if($userposition==1){
                $val=null;
            }
            $output .= '<option value="'.$val.'">NA</option>';
        }
        
        echo $output;

    }

    

    function fetch_employee_data(Request $request){
        $id = $request->id;
        $data=[];
        $data['userinfo'] = UserInfo::find($id);
        $data['user'] = User::where('uid','=',$id)->get();
        $data['userbenefit'] = UserBenefit::where('user_info_id','=',$id)->get();
        $data['accesslevelhierarchy'] = AccessLevelHierarchy::where('child_id','=',$id)->get();
        return json_encode($data);
    }

   

    public function update_status(Request $request){
        $user = UserInfo::where('id', $request->status_id)->first();
        var_dump($request->status_reason);
        $user->status = $request->status_data;
        $user->status_reason=$request->status_reason;
        $deactive_date=null;
        if($request->status_data=='Inactive'){
            $deactive_date = Carbon::now();
        }else if($request->status_data=='New_Hired' || $request->status_data=='Active'){
            $deactive_date = null;
        }
        $user->separation_date = $deactive_date;
        $saved = $user->save();
        if($saved){
            $etv = new ExcelTemplateValidator;
            $etv = $etv->updateExcelToken("Reassign");
        }
        $account = User::where('uid', '=',$request->status_id)
                   ->first(); 
        $userInfo = UserInfo::where('id', '=',$request->status_id)
                   ->first(); 
        $data = array(
               'name' => $userInfo->firstname,
               'email' => $account->email
                );
        // if($request->status_data=="new_hired"){
        //  Mail::send([],[],function($message) use ($data){
        //         $message->to($data['email'],'Hello Mr/Mrs '.$data['name'])->subject('Activation Of Account of Mr/Mrs '.$data['name'])
        //         ->setBody('Hello Mr/Mrs '.$data['name'].', This is to inform you that your account has been activated by the HR. Thank You!. ');
        //         $message->from('bfjax5@gmail.com','CNM BPO');
//          });     
        // }else 
            if($request->status_data=="inactive"){
            Mail::send([],[],function($message) use ($data){
                    $message->to($data['email'],'Hello Mr/Mrs '.$data['name'])->subject('Inactive Mail of Mr/Mrs '.$data['name'])
                    ->setBody('Hello Mr/Mrs '.$data['name'].', This is to inform you that your account has been terminated by the HR');
                    $message->from('hr@cnmsolutions.net','CNM Solutions');
            }); 
        }
        return json_encode($request->status_reason);
    }

    public function get_status(Request $request){
        $user = UserInfo::where('id', $request->id)->get();
        return $user;
    }

    public function add_position(Request $request){

        $stripped = str_replace(' ', '', $request->position_name);
        $code = strtolower($stripped);

        $access_level = new AccessLevel;
        $access_level->code = $code;
        $access_level->name = $request->position_name;
        $access_level->parent = $request->position_designation;
        $saved = $access_level->save();
        if($saved){
            $etv = new ExcelTemplateValidator;
            $etv = $etv->updateExcelToken("Add");
        }
        return $access_level;
    }
    
    public function get_position(Request $request, $option = null){
        if($option === 'level') {
            $access_level = AccessLevel::select('name')->groupBy('name')->get();
            return Datatables::of($access_level)->toJson();
        } else {
            $access_level = AccessLevel::all();
            return $access_level;
        }
    }


    
}