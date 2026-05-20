<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;  // ← AGREGAR

class LoginController extends Controller
{
    use ThrottlesLogins;  // ← AGREGAR

    protected $redirectTo = '/';
    protected $maxAttempts = 5;      // ← AGREGAR: 5 intentos
    protected $decayMinutes = 2;    // ← AGREGAR: bloqueo 15 minutos

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username()  // ← AGREGAR ESTE MÉTODO
    {
        return 'correo';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Si hay demasiados intentos, Laravel automáticamente bloquea
        if (method_exists($this, 'hasTooManyLoginAttempts') && 
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            return back()->withErrors([
                'correo' => "Demasiados intentos. Intente de nuevo en {$seconds} segundos."
            ]);
        }

        $user = \App\Models\Usuario::where('correo', $request->input('correo'))->first();

        if (!$user || !Hash::check($request->input('contraseña'), $user->contraseña)) {
            $this->incrementLoginAttempts($request);  // ← Registrar intento fallido
            return back()->withErrors([
                'correo' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('correo');
        }

        // Resetear contador de intentos al iniciar sesión exitosamente
        $this->clearLoginAttempts($request);

        Auth::login($user, $request->boolean('remember'));
        return redirect()->intended($this->redirectTo);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|string',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}