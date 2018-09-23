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
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'contact' => 'required',
            'email' => 'required|unique:users|email',
            'position' => 'required',
            'salary' => 'required',
            'designation'=>'required',
        ],$validation_message);


        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }


        $userinfo = new UserInfo;
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
            $userinfo->image = base64_encode($binaryfile);
            $userinfo->save();
        }
        $userinfo->save();

        $user = new User;
        $user->uid= $userinfo->id;
        $user->email = $request->email;
        $user->password = '123456';
        $user->access_id = $request->position;
        $user->save();

        $obj_benefit[]=array();
        $obj_benefit = [[
            'user_info_id'=>$userinfo->id,
            'benefit_id'=>1,
            'id_number'=>$request->sss,
            ],
            [
            'user_info_id'=>$userinfo->id,
            'benefit_id'=>2,
            'id_number'=>$request->phil_health,
            ],
            [
            'user_info_id'=>$userinfo->id,
            'benefit_id'=>3,
            'id_number'=>$request->pag_ibig,
            ],
            [
            'user_info_id'=>$userinfo->id,
            'benefit_id'=>4,
            'id_number'=>$request->tin,
            ]];


        UserBenefit::insert($obj_benefit);

        $access_level_hierarchy = new AccessLevelHierarchy;
        $access_level_hierarchy->child_id = $userinfo->id;
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
        $output = '<option value="">Select '.$data[0]->accesslevelname.'</option>';
        foreach($data as $datum)
        {
            $output .= '<option value="'.$datum->id.'">'.$datum->lastname.", ".$datum->firstname." ".$datum->middlename.'</option>';
        }
        echo $output;

    }

    
}
