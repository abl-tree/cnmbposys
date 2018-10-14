<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/' ;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public  function login(Request $request)
    {
       
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if($user){
            $status = $user->info->status;
            $flag = $user->loginFlag;
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed
                if($status == 'Active'){
                    if($flag === false){
                        return redirect()->intended('/profile');
                    }
                   else{
                        return redirect()->to('/security');
                    }
                }else{
                    // User is terminated, redirect back to login
                    Auth::logout();
                    return redirect()->to('/login')->withErrors(['email'=>'You have been terminated.']);;
                }
            
                
        }
        else{
            // User is not valid, redirect back to login here
            return redirect()->to('/login')->withErrors(['email'=>'These credentials do not match our records.']);
        }         
    }

  

    } 

