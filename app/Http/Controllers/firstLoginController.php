<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
class firstLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('firstLogin');
    }
    public function index()
    {
        return view('firstLoginCheck');
    }
    public function updatePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'pass' => 'required|string|max:200',

        ]);
        
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
       }
        $users = User::find(auth()->user()->id);
          $users->password =$request->pass;
          $users->loginFlag = 1;
          $users->save();
    }
}
