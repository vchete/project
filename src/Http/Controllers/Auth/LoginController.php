<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\Auth\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
        $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ]);


        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);
        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            if (!Auth::user()->activo) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'El usuario se encuentra inactivo',
                ]);
            }
            
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
