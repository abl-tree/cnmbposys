<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserReport;
use App\UserInfo;
use Mail;

class UserController extends Controller
{
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
        if($request->description==''){
            return json_encode("Error");
        }else{

            $IR = new UserReport;
            $IR->user_reports_id = $request->id;
            $IR->description = $request->description;
            $IR->save(); 
            $IRcount = UserReport::where('user_reports_id','=',$request->id)->count();
            $user = User::where('uid', '=',$request->id)
                   ->first(); 
            $userInfo =  UserInfo::where('id', '=',$request->id)
                   ->first(); 
            $data = array(
               'name' => $userInfo->firstname,
               'email' => $user->email
                    );
            if($userInfo->status=="Active" && $IRcount%3==0){
             $userInfo->status = "Terminated";
             $userInfo->save();  

              Mail::send(['text'=>'mail'],$data,function($message) use ($data){
                $message->to($data['email'],'Hello Mr/Mrs '.$data['name'])->subject('Termination Mail of Mr/Mrs '.$data['name']);
                $message->from('bfjax5@gmail.com','CNM BPO');
                 });         
                }   
           
                return json_encode('success'); 
            }

    }

    //Add IR END

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

