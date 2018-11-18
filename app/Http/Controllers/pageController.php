<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccessLevel;
use App\AccessLevelHierarchy;
use App\User;
use App\UserInfo;

class pageController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('loginVerif');
    }

    public function index(){
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);
        $emp = AccessLevelHierarchy::with('childInfo.user.access')->orderBy('parent_id')->get();

        $userInfo = AccessLevel::all();
        return view('admin.dashboard.dashboard', compact('profile', 'role', 'user', 'userInfo', 'emp'));
    }
}
