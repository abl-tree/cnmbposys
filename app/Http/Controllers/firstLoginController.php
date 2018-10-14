<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
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
        $users = User::find(auth()->user()->id);
          $users->password =$request->pass;
          $users->loginFlag = 1;
          $users->save();
    }
}
