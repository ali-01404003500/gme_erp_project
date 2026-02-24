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
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        $title = 'Login';
        $description = 'Login to your account';

        return view('auth.login', compact('title', 'description'));
    }

    /**
     * IMPORTANT: Only allow active users to login (Web)
     */
    protected function credentials(Request $request)
    {
        $field = $this->username();

        return [
            $field => $request->input('email'),
            'password' => $request->password,
            'user_status' => 'active', // change to 1 if numeric
        ];
    }

    /**
     * Custom failed login message (Inactive check)
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $field = $this->username();

        $user = User::where($field, $request->input('email'))->first();

        // User not found
        if (!$user) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['This username/email does not exist.'],
            ]);
        }

        // Password incorrect
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => ['Incorrect password.'],
            ]);
        }

        // Account inactive
        if ($user->user_status != 'active') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact admin.'],
            ]);
        }

        // Default fallback
        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * After login success
     */
    protected function authenticated(Request $request, $user)
    {
        Cache::put('user-is-online-' . $user->id, true, 43200);

        $generalNotificationService = new GeneralNotificationService();
        $generalNotificationService->updateNotificationCount($user->id);
    }

    /**
     * Login field detection (email or employee_full_id)
     */
    public function username()
    {
        if (is_numeric(request()->email)) {
            return 'employee_full_id';
        }
        return 'email';
    }

    /**
     * Logout (Web)
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

            return redirect($path);

        } catch (\Throwable $th) {
            return redirect()->route('login')->withError('Login Session Timed Out!');
        }
    }

    private function removeUserFromCache()
    {
        Cache::forget('logged-in-users-' . auth()->id());
    }

    /**
     * ============================
     * API LOGIN (JWT)
     * ============================
     */

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

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $field = is_numeric($request->email) ? 'employee_full_id' : 'email';

        $user = User::where($field, $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->user_status != 'active') {
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        $credentials = [
            $field => $request->email,
            'password' => $request->password,
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $this->authenticated($request, auth()->user());

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => auth()->user()
        ]);
    }

    public function logoutApi(Request $request){

    }
}
