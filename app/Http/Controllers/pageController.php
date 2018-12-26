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
    }


    public function dashboard(){
        $id = auth()->user()->id;
        $access_level = auth()->user()->access_id;
        $role = AccessLevel::find($access_level);
        $profile = UserInfo::with('benefits')->find($id);
        $user = User::find($id);
        $emp = AccessLevelHierarchy::with('childInfo.user.access')->orderBy('parent_id')->get();

        $userInfo = AccessLevel::all();

        if($access_level==12 || $access_level==13 || $access_level==14){
            
        }else if($access_level<4){
            return view('admin.dashboard.hr', compact('profile', 'role', 'user', 'userInfo', 'emp'));
        }else{
            $underconstruction = "/images/underconstruction.png";
            return view('admin.dashboard.underconstruction', compact('profile', 'role', 'user', 'userInfo', 'emp','underconstruction'));
        }
    }

    public function schedule(){
        return view('admin.dashboard.rta');
    }
}
