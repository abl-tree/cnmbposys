<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if ($user) {
            $status = $user->info->status;
            $flag = $user->loginFlag;
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed
            if ($status == 'active' || $status == 'new_hired') {
                if ($flag === false) {
                    return redirect()->intended('/profile');
                } else {
                    return redirect()->to('/security');
                }
            } else {
                // User is terminated, redirect back to login
                Auth::logout();
                return redirect()->to('/login')->withErrors(['email' => 'You have been terminated.']);
            }

        } else {
            // User is not valid, redirect back to login here
            return redirect()->to('/login')->withErrors(['email' => 'These credentials do not match our records.']);
        }
    }

    /**
     * New login method using passport
     *
     * @param Request $request
     * @return mixed
     */
    public function loginPassport(Request $request)
    {
        $data = $request->all();
        $data['grant_type'] = 'password';
        $data['client_id'] = env("ACCOUNTS_API_CLIENT_ID");
        $data['client_secret'] = env("ACCOUNTS_API_CLIENT_SECRET");
        $data['scope'] = '*';

        if (!isset($data['username']) || !isset($data['password'])) {
            return response()->json([
                'code' => 500,
                'title' => 'Incomplete credetials',
            ], 500);
        }

        if (Auth::attempt(['email' => $data['username'], 'password' => $data['password']])) {
            $user = Auth::user();
            $success['access_token'] = $user->createToken('CNM')->accessToken;
            return response()->json(
                [
                    'code' => 200,
                    "title" => 'Successfully logged in',
                    'meta' => [
                        'token' => $success,
                        'user' => $user,
                    ],
                ], 200);
        } else {
            return response()->json(
                [
                    'code' => 401,
                    "title" => 'Unauthorized access | Invalid credentials',
                    'meta' => [
                        'username' => $data['username'],
                    ],
                ], 401);
        }
    }

}
