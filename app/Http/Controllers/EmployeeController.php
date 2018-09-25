<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\UserInfo;
use App\UserBenefit;
use App\AccessLevelHierarchy;


class EmployeeController extends Controller
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
        //
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
        $validation_message = [
            'email.unique'=> 'This email is already used.',
        ];

        $user="";
        $userinfo="";
        $access_level_hierarchy="";
        $email = "";   
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'contact' => 'required',
            'email' => $email,
            'position' => 'required',
            'salary' => 'required',
            'designation'=>'required',
        ],$validation_message);


        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        
        
////////////////////////if
        if($request->action=='add'){
            $userinfo = new UserInfo;
            $user = new User;
            $access_level_hierarchy = new AccessLevelHierarchy;
            $email = 'required|unique:users|email';
        }else if($request->action=='edit'){
            $userinfo = UserInfo::find($request->id);
            $user = User::find($request->id);
            $access_level_hierarchy = AccessLevelHierarchy::where('child_id','=',$request->id)->first();
            $email = 'required|email';
        }
////////////////////////endif
        
        $userinfo->firstname=$request->first_name;
        $userinfo->lastname=$request->last_name;
        $userinfo->middlename=$request->middle_name;
        $userinfo->address=$request->address;
        $userinfo->birthdate=$request->birthdate;
        $userinfo->gender=$request->gender;
        $userinfo->salary_rate=$request->salary;
        $userinfo->contact_number=$request->contact;
        if($request->hasFile('photo')){
            $binaryfile = file_get_contents($_FILES['photo']['tmp_name']);
            $userinfo->image_ext= explode(".", strtolower($_FILES['photo']['name']))[1];
            $userinfo->image = $binaryfile;
            $userinfo->save();
        }
        $userinfo->save();

        
        $user->uid= $userinfo->id;
        $user->email = $request->email;
        $user->password = '123456';
        $user->access_id = $request->position;
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
        
        $access_level_hierarchy->parent_id = $request->designation;
        $check = $access_level_hierarchy->save();
        if($check){
            return response()->json(['success'=>'Record is successfully added']);
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
        $val= $request->get('value');

        if($val>1){
           $val = $val - 1;
        }

        $data = DB::table('users')
                    ->join('access_levels','users.access_id','=','access_levels.id')
                    ->join('user_infos','user_infos.id','=','users.uid')
                    ->select('user_infos.id','user_infos.firstname','user_infos.lastname','user_infos.middlename','access_levels.name as accesslevelname')
                    ->where('access_levels.id','=',$val)
                    ->get();
        $output="";
        if($data->count()>0){
            $output.= '<option value="">Select '.$data[0]->accesslevelname.'</option>';
            foreach($data as $datum){
                $output .= '<option value="'.$datum->id.'">'.$datum->lastname.", ".$datum->firstname." ".$datum->middlename.'</option>';
            }
        }else{
            $output .= '<option value="">NA</option>';
        }
        
        echo $output;

    }

    

    function fetch_employee_data(Request $request){
        $id = $request->id;
        $data=[];
        $data['userinfo'] = UserInfo::select('id','firstname','middlename','lastname','birthdate','gender','address','contact_number','salary_rate')->find($id);
        $data['user'] = User::where('uid','=',$id)->get();
        $data['userbenefit'] = UserBenefit::where('user_info_id','=',$id)->get();
        $data['accesslevelhierarchy'] = AccessLevelHierarchy::where('child_id','=',$id)->get();
        return json_encode($data);
    }

    function fetch_blob_image(Request $request){
        $id = $request->id;
        $data = UserInfo::select('image','image_ext')->find($id);
        if($data->count()>0){
            echo 'data:image/'.$data->image_ext.';base64, '.base64_encode($data->image);
        }else{
            echo 'no result';
        }
    }
    
    
}
