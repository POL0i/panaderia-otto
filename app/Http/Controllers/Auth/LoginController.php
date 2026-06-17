<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{
    use ThrottlesLogins;

    protected $redirectTo = '/';
    protected $maxAttempts = 5;     // Bloquea tras 5 intentos fallidos
    protected $decayMinutes = 15;   // Bloqueo por 15 minutos

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Campo usado para identificar al usuario (correo en lugar de email)
     */
    public function username(): string
    {
        return 'correo';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validar formato del input
        $request->validate([
            'correo'    => ['required', 'email', 'max:255'],
            'contraseña' => ['required', 'string', 'min:4', 'max:128'],
            // Honeypot: si este campo oculto tiene contenido, es un bot
            'website'   => ['size:0'],
        ], [
            'website.size' => 'Error de validación.',
        ]);

        // 2. Bloquear si demasiados intentos fallidos
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($request));
            return back()->withErrors([
                'correo' => "Demasiados intentos fallidos. Espera {$seconds} segundos.",
            ])->withInput($request->only('correo'));
        }

        // 3. Sanitizar el correo (strip_tags + trim)
        $correo = strip_tags(trim($request->input('correo')));

        // 4. Buscar usuario
        $user = \App\Models\Usuario::where('correo', $correo)->first();

        if (!$user || !Hash::check($request->input('contraseña'), $user->contraseña)) {
            $this->incrementLoginAttempts($request);
            return back()->withErrors([
                'correo' => 'Las credenciales no coinciden con nuestros registros.',
            ])->withInput($request->only('correo'));
        }

        // 5. Verificar que el usuario esté activo
        if ($user->estado !== 'activo') {
            return back()->withErrors([
                'correo' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->withInput($request->only('correo'));
        }

        // 6. Login exitoso — limpiar intentos y regenerar sesión
        $this->clearLoginAttempts($request);
        $request->session()->regenerate();

        Auth::login($user, $request->boolean('remember'));
        return redirect()->intended($this->redirectTo);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}