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
    
    <!-- Tema personalizado -->
    <link rel="stylesheet" href="{{ asset('css/panadria-theme.css') }}">
    
    <style>
        :root {
            --color-primary-dark: #5D3A1A;
            --color-primary-medium: #8B4513;
            --color-primary-light: #A0522D;
            --color-accent: #D2B48C;
            --color-accent-hover: #C4A67A;
            --color-bg-light: #FFF9F0;
            --color-bg-lighter: #FFF5E6;
            --color-text-dark: #3E2723;
            --color-text-medium: #5D4037;
            --color-text-light: #8D6E63;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary-medium) 50%, var(--color-primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ========== LOGIN PRINCIPAL ========== */
        .login-wrapper {
            width: 100%;
            max-width: 480px;
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

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        /* Header del Login */
        .login-header {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary-medium) 100%);
            padding: 45px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
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
            color: white;
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
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.15);
        }

        .login-header .subtitle {
            margin: 8px 0 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
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

        /* Body del Login */
        .login-body {
            padding: 35px;
            background: var(--color-bg-light);
        }

        .user-type-info {
            background: linear-gradient(135deg, rgba(93, 58, 26, 0.05) 0%, rgba(139, 69, 19, 0.05) 100%);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 25px;
            border-left: 4px solid var(--color-primary-medium);
            font-size: 13px;
            color: var(--color-primary-dark);
        }

        .user-type-info i {
            color: var(--color-primary-medium);
            margin-right: 10px;
        }

        /* Campos del formulario */
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
            color: var(--color-primary-medium);
            font-size: 16px;
            z-index: 10;
            pointer-events: none;
        }

        .input-group-icon .form-control {
            padding-left: 45px;
            border-radius: 12px;
            border: 2px solid #E0D5C1;
            height: 50px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .input-group-icon .form-control:focus {
            border-color: var(--color-primary-medium);
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.15);
        }

        .input-group-icon .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--color-primary-medium);
        }

        .form-check-label {
            font-size: 13px;
            color: var(--color-primary-dark);
            cursor: pointer;
        }

        .forgot-password a {
            color: var(--color-primary-medium);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s ease;
        }

        .forgot-password a:hover {
            color: var(--color-primary-dark);
            text-decoration: underline;
        }

        /* Botones */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);
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
            box-shadow: 0 12px 25px rgba(93, 58, 26, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: var(--color-accent);
            color: var(--color-primary-dark);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: var(--color-accent-hover);
            transform: translateY(-2px);
        }

        /* Divisor */
        .divider {
            text-align: center;
            margin: 25px 0 15px;
            position: relative;
            color: var(--color-text-light);
            font-size: 12px;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--color-accent);
        }

        .divider span {
            background: var(--color-bg-light);
            padding: 0 15px;
            position: relative;
            font-weight: 500;
        }

        /* Footer */
        .login-footer {
            background: var(--color-bg-lighter);
            text-align: center;
            padding: 20px;
            font-size: 11px;
            color: var(--color-text-light);
            border-top: 1px solid var(--color-accent);
        }

        .login-footer p {
            margin: 0;
            line-height: 1.5;
        }

        /* Alertas */
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

        .alert ul {
            padding-left: 20px;
        }

        /* ========== MODAL DE REGISTRO ========== */
        .modal-content {
            border-radius: 15px;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary-medium) 100%);
            border-radius: 15px 15px 0 0;
            border-bottom: none;
            padding: 20px 25px;
        }

        .modal-title {
            color: white;
            font-weight: 600;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            background: var(--color-bg-light);
            padding: 25px;
        }

        .modal-body label {
            font-weight: 500;
            color: var(--color-text-dark);
            margin-bottom: 5px;
            font-size: 14px;
        }

        .modal-body .form-control {
            border-radius: 10px;
            border: 2px solid #E0D5C1;
            height: 45px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .modal-body .form-control:focus {
            border-color: var(--color-primary-medium);
            box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.15);
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .requirement-list {
            font-size: 0.85rem;
            margin-top: 8px;
        }

        .requirement-list li {
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .requirement-list i {
            margin-right: 5px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-wrapper {
                padding: 10px;
            }

            .login-header {
                padding: 35px 20px;
            }

            .login-header h1 {
                font-size: 26px;
            }

            .logo-icon {
                font-size: 55px;
            }

            .login-body {
                padding: 25px 20px;
            }

            .modal-body {
                padding: 20px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    {{-- ==================== LOGIN PRINCIPAL ==================== --}}
    <div class="login-wrapper">
        <div class="login-card">
            {{-- Header --}}
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
            
            {{-- Body --}}
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
                
                {{-- Formulario de Login --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    {{-- Email --}}
                    <div class="form-group">
                        <div class="input-group-icon">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
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
                    
                    {{-- Contraseña --}}
                    <div class="form-group">
                        <div class="input-group-icon">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
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
                    
                    {{-- Recordar y Olvidé contraseña --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>
                    
                    {{-- Botón Login --}}
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                </form>

                {{-- Registro --}}
                <div class="divider">
                    <span>¿Nuevo cliente?</span>
                </div>
                
                <div class="text-center">
                    <button type="button" class="btn btn-register" id="btnRegistroRapido">
                        <i class="fas fa-user-plus me-2"></i> Registrarse como Cliente
                    </button>
                    <p class="mt-3" style="font-size: 12px; color: var(--color-text-light);">
                        Al registrarte podrás realizar pedidos y ver tu historial de compras
                    </p>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="login-footer">
                <p>
                    <i class="fas fa-copyright me-1"></i>
                    {{ date('Y') }} Panadería Otto. Todos los derechos reservados.
                </p>
                <p class="mt-2" style="font-size: 10px; opacity: 0.7;">
                    <i class="fas fa-shield-alt me-1"></i> Sistema seguro de gestión
                </p>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DE REGISTRO RÁPIDO ==================== --}}
    <div class="modal fade" id="registroRapidoModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- Header del Modal --}}
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i> Registro Rápido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                {{-- Body del Modal --}}
                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Completa tus datos para crear una cuenta. Podrás realizar pedidos fácilmente.
                    </div>
                    
                    <form id="formRegistroRapido" action="{{ route('registro.cliente.rapido') }}" method="POST" novalidate>
                        @csrf
                        
                        {{-- Nombre y Apellido --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reg-nombre" class="form-label">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="nombre" 
                                       id="reg-nombre" 
                                       class="form-control" 
                                       placeholder="Tu nombre"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reg-apellido" class="form-label">
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="apellido" 
                                       id="reg-apellido" 
                                       class="form-control" 
                                       placeholder="Tu apellido"
                                       required>
                            </div>
                        </div>
                        
                        {{-- Correo --}}
                        <div class="mb-3">
                            <label for="reg-correo" class="form-label">
                                Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="correo" 
                                   id="reg-correo" 
                                   class="form-control" 
                                   placeholder="correo@ejemplo.com"
                                   required>
                            <small class="text-muted">Este será tu usuario para iniciar sesión</small>
                        </div>
                        
                        {{-- Teléfono (AHORA OBLIGATORIO) --}}
                        <div class="mb-3">
                            <label for="reg-telefono" class="form-label">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                   name="telefono" 
                                   id="reg-telefono" 
                                   class="form-control" 
                                   placeholder="Ej: +56912345678"
                                   pattern="^\+?[0-9]{8,15}$"
                                   required>
                            <small class="text-muted">Necesario para coordinar tus pedidos</small>
                            <div class="invalid-feedback">
                                Ingresa un número de teléfono válido (8-15 dígitos, permite + al inicio)
                            </div>
                        </div>
                        
                        {{-- Contraseña --}}
                        <div class="mb-3">
                            <label for="reg-password" class="form-label">
                                Contraseña <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       name="contraseña" 
                                       id="reg-password" 
                                       class="form-control" 
                                       placeholder="Mínimo 8 caracteres"
                                       required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            {{-- Requisitos de contraseña --}}
                            <div class="requirement-list">
                                <small class="text-muted">La contraseña debe cumplir:</small>
                                <ul class="list-unstyled mb-0 mt-1">
                                    <li id="req-length" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Mínimo 8 caracteres
                                    </li>
                                    <li id="req-uppercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos 1 mayúscula
                                    </li>
                                    <li id="req-lowercase" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos 1 minúscula
                                    </li>
                                    <li id="req-number" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos 2 números
                                    </li>
                                    <li id="req-special" class="text-danger">
                                        <i class="fas fa-times-circle"></i> Al menos 1 carácter especial (!@#$%^&*)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        {{-- Confirmar Contraseña --}}
                        <div class="mb-3">
                            <label for="reg-password-confirm" class="form-label">
                                Confirmar Contraseña <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   name="contraseña_confirmation" 
                                   id="reg-password-confirm" 
                                   class="form-control" 
                                   placeholder="Repite tu contraseña"
                                   required>
                        </div>
                        
                        {{-- Términos y Condiciones --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar los términos y condiciones
                            </div>
                        </div>
                        
                        {{-- Botón Enviar --}}
                        <button type="submit" class="btn btn-primary w-100" style="background: var(--color-primary-medium); border: none; padding: 12px; border-radius: 50px; font-weight: 600;">
                            <i class="fas fa-check-circle me-2"></i> Registrarse
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DE TÉRMINOS ==================== --}}
    <div class="modal fade" id="terminosModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>Al registrarte en <strong>Panadería Otto</strong>, aceptas:</p>
                    <ul>
                        <li>Proporcionar información veraz y actualizada.</li>
                        <li>Mantener la confidencialidad de tu contraseña.</li>
                        <li>Aceptar nuestras políticas de privacidad y tratamiento de datos.</li>
                    </ul>
                    <p class="mb-0">Para más información, contacta con nuestro equipo de soporte.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ==================== SCRIPTS ==================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== REFERENCIAS A ELEMENTOS ==========
            const btnRegistro = document.getElementById('btnRegistroRapido');
            const registroModalEl = document.getElementById('registroRapidoModal');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('reg-password');
            const formRegistro = document.getElementById('formRegistroRapido');
            
            let registroModal;
            
            // Inicializar modal de Bootstrap
            if (registroModalEl) {
                registroModal = new bootstrap.Modal(registroModalEl);
            }
            
            // ========== ABRIR MODAL DE REGISTRO ==========
            if (btnRegistro && registroModal) {
                btnRegistro.addEventListener('click', function() {
                    registroModal.show();
                });
            }
            
            // ========== TOGGLE MOSTRAR/OCULTAR CONTRASEÑA ==========
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
            
            // ========== VALIDACIÓN DE CONTRASEÑA EN TIEMPO REAL ==========
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    
                    const requirements = {
                        'req-length': password.length >= 8,
                        'req-uppercase': /[A-Z]/.test(password),
                        'req-lowercase': /[a-z]/.test(password),
                        'req-number': (password.match(/\d/g) || []).length >= 2,
                        'req-special': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                    };
                    
                    for (const [id, isValid] of Object.entries(requirements)) {
                        updateRequirement(id, isValid);
                    }
                });
            }
            
            /**
             * Actualiza el estado visual de un requisito de contraseña
             * @param {string} elementId - ID del elemento li
             * @param {boolean} isValid - Si el requisito se cumple
             */
            function updateRequirement(elementId, isValid) {
                const element = document.getElementById(elementId);
                if (!element) return;
                
                const icon = element.querySelector('i');
                if (isValid) {
                    element.className = 'text-success';
                    if (icon) icon.className = 'fas fa-check-circle';
                } else {
                    element.className = 'text-danger';
                    if (icon) icon.className = 'fas fa-times-circle';
                }
            }
            
            // ========== VALIDACIÓN FINAL AL ENVIAR ==========
            if (formRegistro) {
                formRegistro.addEventListener('submit', function(e) {
                    const password = passwordInput ? passwordInput.value : '';
                    const confirmInput = document.getElementById('reg-password-confirm');
                    const confirm = confirmInput ? confirmInput.value : '';
                    const terminosCheck = document.getElementById('terminos');
                    
                    let errors = [];
                    
                    // Validar requisitos de contraseña
                    if (password.length < 8) {
                        errors.push('• La contraseña debe tener al menos 8 caracteres');
                    }
                    if (!/[A-Z]/.test(password)) {
                        errors.push('• La contraseña debe tener al menos 1 mayúscula');
                    }
                    if (!/[a-z]/.test(password)) {
                        errors.push('• La contraseña debe tener al menos 1 minúscula');
                    }
                    if ((password.match(/\d/g) || []).length < 2) {
                        errors.push('• La contraseña debe tener al menos 2 números');
                    }
                    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                        errors.push('• La contraseña debe tener al menos 1 carácter especial');
                    }
                    
                    // Validar coincidencia de contraseñas
                    if (password !== confirm) {
                        errors.push('• Las contraseñas no coinciden');
                    }
                    
                    // Validar términos y condiciones
                    if (terminosCheck && !terminosCheck.checked) {
                        errors.push('• Debes aceptar los términos y condiciones');
                    }
                    
                    // Mostrar errores si existen
                    if (errors.length > 0) {
                        e.preventDefault();
                        alert('Por favor corrige lo siguiente:\n\n' + errors.join('\n'));
                        return false;
                    }
                });
            }
            
            // ========== LIMPIAR MODAL AL CERRAR ==========
            if (registroModalEl) {
                registroModalEl.addEventListener('hidden.bs.modal', function() {
                    if (formRegistro) {
                        formRegistro.reset();
                        
                        // Resetear requisitos de contraseña
                        const reqIds = ['req-length', 'req-uppercase', 'req-lowercase', 'req-number', 'req-special'];
                        reqIds.forEach(id => updateRequirement(id, false));
                    }
                });
            }
        });
    </script>
</body>
</html>