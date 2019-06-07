<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\User;
use App\Data\Models\UserReport;
use App\Data\Models\UserInfo;
use Mail;
use Auth;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\UsersInfoRepository;

class UserController extends BaseController
{

    protected $user_info;

    public function __construct(
        UsersInfoRepository $user_info
    ){
        $this->user_info = $user_info;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::latest('updated_at')->get();

        return view('admin.users.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    public function usersInfo(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_info->usersInfo($data))->json();     
    }

    public function addUser(Request $request)
    {
        $data = $request->all();
        request()->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

        ]);
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        $data['imageName']= $imageName;
        return $this->absorb($this->user_info->addUser($data))->json();     
    }

    public function updateStatus(Request $request)
    {       
        $data = $request->all();
        return $this->absorb($this->user_info->updateStatus($data))->json();
    }
    public function bulkUpdateStatus(Request $request)
    {       
        $data = $request->all();
        return $this->absorb($this->user_info->bulkUpdateStatus($data))->json();
    }
    public function userInfo(Request $request, $id)
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

        return $this->absorb($this->user_info->usersInfo($data))->json();
    }
    public function getCluster(Request $request, $id)
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

        return $this->absorb($this->user_info->getCluster($data))->json();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, User::rules());
        
        User::create($request->all());

        return back()->withSuccess(trans('app.success_store'));
    }


    //Add IR START

     public function add_IR(Request $request)
    {   
        $logged_in = Auth::user()->uid;
        if($request->description == ''){
            return json_encode("Error");
        }else{

            $IR = new UserReport;
            $IR->user_reports_id = $request->id;
            $IR->description = $request->description;
            $IR->filed_by = $logged_in;
            $IR->save(); 
            $IRcount = UserReport::where('user_reports_id','=',$request->id)->count();
            $user = User::where('uid', '=',$request->id)->first(); 
            $userInfo =  UserInfo::where('id', '=',$request->id)->first(); 
            $data = array(
               'name' => $userInfo->firstname,
               'email' => $user->email
            );
            if($userInfo->status=="Active" && $IRcount%3==0){
                $userInfo->status = "Warning";
                $userInfo->save();  
                Mail::send([],[],function($message) use ($data){
                    $message->to($data['email'],'Hello Mr/Mrs '.$data['name'])->subject('Warning Mail of Mr/Mrs '.$data['name'])
                    ->setBody('Hello Mr/Mrs '.$data['name'].', This is to inform you that your account status has been set to "WARNING" due to multiple Incident Reports filed.');
                    $message->from('bfjax5@gmail.com','CNM Solutions');
                });     
            }   
            return json_encode('success'); 
        }
    }

    //Add IR END

    //Get IR for viewing each employee IR's
    public function get_ir(Request $request){   
        $id = $request->input('id');

        $reports_details = DB::table('user_reports')
        ->join('users','users.id','=','user_reports.user_reports_id')
        ->join('user_infos','user_infos.id','=','user_reports.filed_by')
        ->select('user_reports.id','description as description','user_reports.created_at as date_filed','firstname','lastname','middlename')
        ->where('user_reports.user_reports_id','=',$id)->get();

        return Datatables::of($reports_details)
        ->editColumn('date_filed', function ($data){
            return date('F d, Y g:i a', strtotime($data->date_filed));
        })->make(true);
         echo json_encode($reports_details);
       
    }

    //end of function

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
        $item = User::findOrFail($id);

        return view('admin.users.edit', compact('item'));
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
        $this->validate($request, User::rules(true, $id));

        $item = User::findOrFail($id);

        $item->update($request->all());

        return redirect()->route(ADMIN . '.users.index')->withSuccess(trans('app.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);

        return back()->withSuccess(trans('app.success_destroy')); 
    }
}