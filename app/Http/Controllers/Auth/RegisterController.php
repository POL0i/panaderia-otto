<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Honeypot: campo invisible que solo los bots llenan
            'website'    => ['size:0'],

            'correo'     => [
                'required',
                'string',
                'email:rfc,dns',   // valida formato Y que el dominio exista
                'max:255',
                'unique:usuarios',
            ],
            'contraseña' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                // Debe tener al menos una letra y un número
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            ],
        ], [
            'website.size'         => 'Error de validación.',
            'contraseña.regex'     => 'La contraseña debe tener al menos una letra y un número.',
            'correo.email'         => 'El correo electrónico no es válido.',
            'correo.unique'        => 'Este correo ya está registrado.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return Usuario
     */
    protected function create(array $data)
    {
        return Usuario::create([
            // Sanitizar correo: quitar espacios y etiquetas HTML
            'correo'     => strtolower(strip_tags(trim($data['correo']))),
            'contraseña' => Hash::make($data['contraseña']),
            'estado'     => 'activo',
        ]);
    }
}
