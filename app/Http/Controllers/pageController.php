<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Data\Models\UserBenefit;
use App\Data\Models\AccessLevel;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\UserInfo;
use App\User;

class pageController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('loginVerif');
        $this->middleware('pageAccess');
    }


    public function dashboard(){
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);
        $emp = AccessLevelHierarchy::with('childInfo.user.access')->orderBy('parent_id')->get();

        $userInfo = AccessLevel::all();

        $position = '';
        switch($access_level){
            case 2: $position = 'hr'; break;
            case 12: $position = 'rta'; break;
        }
                return view('admin.dashboard.'.$position, compact('profile', 'role', 'user', 'userInfo', 'emp'));


    }

    public function schedule(){
        return view('admin.schedule.rta');
    }
}
