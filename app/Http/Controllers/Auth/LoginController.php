<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'login';
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended('admin/dashboard');
        } elseif ($user->role === 'agent') {
            return redirect()->intended('agent/dashboard');
        } else {
            return redirect()->intended('/dashboard');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        if (auth()->attempt([
            $field => $login,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->authenticated($request, auth()->user());
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => 'These credentials do not match our records.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
