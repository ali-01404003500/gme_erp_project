<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {

        $title = 'Login';
        $description = 'Login to your account';

        return view('auth.login', compact('title', 'description'));
    }

    function authenticated(Request $request, $user)
    {
        Cache::put('user-is-online-' . $user->id, true, 43200);
        $generalNotificationService = new GeneralNotificationService();
        $generalNotificationService->updateNotificationCount($user->id);
    }


    public function username()
    {
        if (is_numeric(request()->email)) {
            return 'employee_full_id';
        }
        return 'email';
    }




    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {

            $this->removeUserFromCache();

            $path = '/login';
            $parts = explode('/', request()->session()->previousUrl());
            if (count($parts) >= 4 && $parts[3] == 'em') {
                $path = '/em';
            }
            $this->guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $this->loggedOut($request) ?: redirect($path);
        } catch (\Throwable $th) {

            return redirect()->route('login')->withError('Login Season Tiemed Out!');
        }
    }

    private function removeUserFromCache()
    {
        Cache::clear('logged-in-users-' . auth()->id());
    }



    public function verifyResetPasswordToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
        ]);

        $data['user'] = User::query()
            ->where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        return view('auth.login', $data);
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'The repeat password does not match.'
        ]);

        $user = User::query()
            ->where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password Reset Successful. Please Login!');
    }


    public function loginApi(Request $request){
        $credentials = $request->only('email', 'password');

        // Use JWTAuth::attempt() to generate the token
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $this->authenticated($request, auth()->user());
        InvalidateAuthUserCashe(auth()->user()->id);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            // 'expires_in'   => Auth::factory()->getTTL() * 60
            'user'   => auth()->user()

        ]);
    }

    public function logoutApi(Request $request){

    }
}
