<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\UserInfo;
use App\UserBenefit;
use App\AccessLevelHeirarchy;


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
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
            'position' => 'required',
            'salary' => 'required',
        ]);


        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

            

            $user = new User;
            $user->email = $request->email;
            $user->password = '123456';
            $user->access_id = $request->position;
            $user->save();
            

            $userinfo = new UserInfo;
            $userinfo->uid= $user->id;
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

            $access_level_heirarchy = new AccessLevelHeirarchy;
            $access_level_heirarchy->child_id = $request->position;
            $access_level_heirarchy->parent_id = $request->designation;
            $access_level_heirarchy->save();





        // return redirect()->route('profile');
        // ->with('success','Item created successfully');

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
                    ->join('user_infos','user_infos.uid','=','users.id')
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
