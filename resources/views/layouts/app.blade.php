<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panadería Otto - Iniciar Sesión')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Tema personalizado de panadería -->
    <link rel="stylesheet" href="{{ asset('css/panadria-theme.css') }}">
    
    <style>

        .btn-register {
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: #C4A67A !important;
            transform: translateY(-2px);
        }

        .modal-content {
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos específicos solo para el login */
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 50%, #A0522D 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            padding: 45px 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(210, 180, 140, 0.1);
            border-radius: 50%;
        }
        
        .login-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: rgba(210, 180, 140, 0.08);
            border-radius: 50%;
        }
        
        .logo-wrapper {
            position: relative;
            z-index: 1;
        }
        
        .logo-icon {
            font-size: 70px;
            margin-bottom: 15px;
            animation: gentleBounce 2s ease-in-out infinite;
        }
        
        @keyframes gentleBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        .login-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.15);
        }
        
        .login-header .subtitle {
            margin: 8px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 300;
        }
        
        .user-type-badge {
            display: inline-block;
            background: rgba(210, 180, 140, 0.25);
            backdrop-filter: blur(10px);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 18px;
            color: white;
            letter-spacing: 0.5px;
        }
        
        .login-body {
            padding: 35px 35px 25px;
            background-color: var(--color-bg-light, #FFF9F0);
        }
        
        .user-type-info {
            background: linear-gradient(135deg, rgba(93, 58, 26, 0.05) 0%, rgba(139, 69, 19, 0.05) 100%);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 25px;
            border-left: 4px solid var(--color-primary-medium, #8B4513);
            font-size: 13px;
            color: var(--color-primary-dark, #5D3A1A);
        }
        
        .user-type-info i {
            color: var(--color-primary-medium, #8B4513);
            margin-right: 10px;
            font-size: 16px;
        }
        
        .user-type-info strong {
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 22px;
        }
        
        .input-group-icon {
            position: relative;
        }
        
        .input-group-icon .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-primary-medium, #8B4513);
            font-size: 16px;
            z-index: 10;
        }
        
        .input-group-icon input {
            padding-left: 45px !important;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--color-primary-medium, #8B4513);
        }
        
        .form-check label {
            margin: 0;
            font-size: 13px;
            color: var(--color-primary-dark, #5D3A1A);
            cursor: pointer;
            font-weight: 400;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        
        .forgot-password a {
            color: var(--color-primary-medium, #8B4513);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s ease;
        }
        
        .forgot-password a:hover {
            color: var(--color-primary-dark, #5D3A1A);
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #8B4513 0%, #5D3A1A 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            box-shadow: 0 8px 20px rgba(93, 58, 26, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #9B5523 0%, #6B4A2A 100%);
            box-shadow: 0 12px 25px rgba(93, 58, 26, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .divider {
            text-align: center;
            margin: 25px 0 15px;
            position: relative;
            color: var(--color-secondary, #A0522D);
            font-size: 12px;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--color-accent, #D2B48C);
        }
        
        .divider span {
            background: var(--color-bg-light, #FFF9F0);
            padding: 0 12px;
            position: relative;
            font-weight: 500;
        }
        
        .login-footer {
            background: var(--color-bg-lighter, #FFF5E6);
            text-align: center;
            padding: 20px;
            font-size: 11px;
            color: var(--color-secondary, #A0522D);
            border-top: 1px solid var(--color-accent, #D2B48C);
        }
        
        .login-footer p {
            margin: 0;
            line-height: 1.5;
        }
        
        /* Alertas personalizadas */
        .alert-custom {
            border-radius: 12px;
            margin-bottom: 20px;
            animation: slideInDown 0.3s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 480px) {
            .login-wrapper {
                padding: 15px;
            }
            .login-body {
                padding: 25px 20px;
            }
            .login-header {
                padding: 35px 25px;
            }
            .login-header h1 {
                font-size: 26px;
            }
            .logo-icon {
                font-size: 55px;
            }
            .btn-login {
                padding: 11px;
                font-size: 14px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-wrapper">
                    <div class="logo-icon">
                        <i class="fas fa-bread-slice"></i>
                    </div>
                    <h1>Panadería Otto</h1>
                    <p class="subtitle">Sistema de Gestión Integral</p>
                    <div class="user-type-badge">
                        <i class="fas fa-lock me-2"></i>
                        Acceso Seguro
                    </div>
                </div>
            </div>
            
            <div class="login-body">
                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-custom">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error de Validación:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Mensaje de estado --}}
                @if (session('status'))
                    <div class="alert alert-success alert-custom">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif
                
                {{-- Información de acceso --}}
                <div class="user-type-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Acceso para:</strong> Administradores, Empleados y Clientes registrados
                </div>
                
                {{-- Formulario de login con los nombres de campo CORRECTOS --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    {{-- Campo correo (NO email) --}}
                    <div class="form-group">
                        <div class="input-group-icon">
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" 
                                   name="correo" 
                                   class="form-control @error('correo') is-invalid @enderror" 
                                   placeholder="Correo electrónico"
                                   value="{{ old('correo') }}" 
                                   required 
                                   autofocus>
                        </div>
                        @error('correo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    {{-- Campo contraseña (NO password) --}}
                    <div class="form-group">
                        <div class="input-group-icon">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" 
                                   name="contraseña" 
                                   class="form-control @error('contraseña') is-invalid @enderror" 
                                   placeholder="Contraseña"
                                   required>
                        </div>
                        @error('contraseña')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Recordarme</label>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                </form>

                   {{-- ✅ NUEVO: Botón y enlace de registro --}}
                    <div class="divider">
                        <span>¿Nuevo cliente?</span>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-register" id="btnRegistroRapido" style="width: 100%; padding: 12px; background: #D2B48C; color: #5D3A1A; border: none; border-radius: 50px; font-weight: 600;">
                            <i class="fas fa-user-plus me-2"></i> Registrarse como Cliente
                        </button>
                        <p class="mt-3" style="font-size: 12px; color: #A0522D;">
                            Al registrarte podrás realizar pedidos y ver tu historial de compras
                        </p>
                    </div>
                </div>
                
                <div class="divider">
                    <span>Panadería Otto</span>
                </div>
            </div>
            
            <div class="login-footer">
                <p>
                    <i class="fas fa-copyright me-1"></i>
                    {{ date('Y') }} Panadería Otto. Todos los derechos reservados.
                </p>
                <p style="margin-top: 8px; font-size: 10px; opacity: 0.7;">
                    <i class="fas fa-shield-alt me-1"></i> Sistema seguro de gestión
                </p>
            </div>
        </div>
    </div>

    {{-- Modal de Registro Rápido --}}
    <div class="modal fade" id="registroRapidoModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-user-plus me-2"></i> Registro Rápido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background: #FFF9F0;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Completa tus datos para crear una cuenta. Podrás realizar pedidos fácilmente.
                    </div>
                    
                    <form id="formRegistroRapido" action="{{ route('registro.cliente.rapido') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Apellido</label>
                                    <input type="text" name="apellido" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label>Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="correo" class="form-control" required>
                            <small class="text-muted">Este será tu usuario para iniciar sesión</small>
                        </div>
                        
                        <div class="mb-3">
                            <label>Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="contraseña" class="form-control" minlength="8" required>
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="mb-3">
                            <label>Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="contraseña_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Teléfono (opcional)</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100" style="background: #8B4513; border: none;">
                            <i class="fas fa-check-circle"></i> Registrarse
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Términos y Condiciones (simple) --}}
    <div class="modal fade" id="terminosModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Al registrarte en Panadería Otto, aceptas:</p>
                    <ul>
                        <li>Proporcionar información veraz y actualizada</li>
                        <li>Mantener la confidencialidad de tu contraseña</li>
                        <li>Aceptar nuestras políticas de privacidad y tratamiento de datos</li>
                    </ul>
                    <p>Para más información, contacta con nuestro equipo de soporte.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    @stack('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const btnRegistro = document.getElementById('btnRegistroRapido');
    const modal = new bootstrap.Modal(document.getElementById('registroRapidoModal'));
    
    if (btnRegistro) {
        btnRegistro.addEventListener('click', function() {
            modal.show();
        });
    }
    
    // Validar confirmación de contraseña
    const form = document.getElementById('formRegistroRapido');
    if (form) {
        form.addEventListener('submit', function(e) {
            const password = form.querySelector('[name="contraseña"]');
            const confirm = form.querySelector('[name="contraseña_confirmation"]');
            
            if (password.value !== confirm.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                confirm.focus();
            }
        });
    }
});
</script>
</body>
</html>