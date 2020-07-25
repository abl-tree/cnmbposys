<?php

namespace App\Http\Controllers\Auth;

use App\Data\Models\UserInfo;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
    protected $maxAttempts = 5;
    protected $decayMinutes = 5;

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

        // max failed login attemps check
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            return response()->json([
                'code' => 429,
                'title' => Lang::get('auth.throttle', ['seconds' => $seconds]),
                'meta' => [
                    'username' => $data['username'],
                ],
            ], 429);
        }

        if (Auth::attempt(['email' => $data['username'], 'password' => $data['password']])) {
            // clear lockout count on successful login
            $this->clearLoginAttempts($request);

            $user = Auth::user();
            $status = UserInfo::find($user->uid)->status;
            $password_updated = Auth::user()->password_updated;
            $loginflag = Auth::user()->loginflag;
            $date = Carbon::parse($password_updated);
            $now = Carbon::now();
            $diff = $date->diffInDays($now) + 1;
            if (strtolower($status) == "active") {
                if ($loginflag !== 0 && $diff > 31) {
                    $success['access_token'] = $user->createToken('CNM')->accessToken;
                    if ($user) {
                        $user['loginFlag'] = 0;
                        $user['password_updated'] = Carbon::now()->toDateTimeString();
                        if (!$user->save($data)) {
                            return $this->setResponse([
                                "code" => 500,
                                "title" => "Data Validation Error on User.",
                                "description" => "An error was detected on one of the inputted data.",
                                "meta" => [
                                    "errors" => $user->errors(),
                                ],
                            ]);
                        } else {
                            return response()->json(
                                [
                                    'code' => 200,
                                    "title" => 'Successfully logged in',
                                    'meta' => [
                                        'token' => $success,
                                        'user' => $user,
                                        'days_difference' => $diff,
                                    ],
                                ], 200);
                        }
                    }

                } else {
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
                }
            } else {
                return response()->json(
                    [
                        'code' => 500,
                        "title" => 'Your account is inactive.',
                        'meta' => [
                            'username' => $data['username'],
                        ],
                    ], 500);
            }
        } else {
            // increase lockout count on failed login
            $this->incrementLoginAttempts($request);

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
