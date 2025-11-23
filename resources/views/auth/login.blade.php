@extends('layouts.app')

@section('title', 'Iniciar sesión - AppNotiDeudores')

@section('styles')
    <style>
        body {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }

        /* Card Principal */
        .login-card {
            background: var(--color-bg-light);
            border-radius: var(--radius-lg);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp var(--duration-slow) var(--ease-out);
        }

        /* Header */
        .login-header {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            opacity: 0.3;
        }

        .login-header-content {
            position: relative;
            z-index: 1;
        }

        .login-logo {
            font-size: 48px;
            margin-bottom: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            margin-left: auto;
            margin-right: auto;
            color: white;
        }

        .login-header h1 {
            font-size: 32px;
            margin: 0 0 8px 0;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
            font-weight: 300;
            color: white;
        }

        /* Body */
        .login-body {
            padding: 50px 40px;
            background: white;
        }

        .login-body h2 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #1f2937;
            font-weight: 700;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 35px;
            font-weight: 400;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
        }

        .form-group i {
            color: #047857;
            width: 18px;
            text-align: center;
        }

        .form-group input {
            width: 100%;
            padding: 13px 16px;
            border: 2px solid #ddd;
            border-radius: var(--radius-base);
            font-size: 14px;
            transition: all var(--duration-normal) ease;
            background: #ffffff;
            color: #1f2937;
            font-family: inherit;
        }

        .form-group input::placeholder {
            color: #d1d5db;
        }

        .form-group input:focus {
            outline: none;
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
            background: #ffffff;
        }

        .form-group input.error {
            border-color: #ef4444;
            background-color: #fef5f5;
        }

        .form-group input.error:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .required {
            color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: none;
            font-weight: 500;
        }

        .error-message.show {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-message i {
            font-size: 12px;
        }

        /* Alertas */
        .alert {
            padding: 14px 16px;
            border-radius: var(--radius-base);
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
            animation: slideIn var(--duration-normal) var(--ease-out);
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid;
        }

        .alert.show {
            display: flex;
        }

        .alert i {
            font-size: 16px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-color: #047857;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-color: #ef4444;
        }

        /* Botón */
        .login-button {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            border: none;
            border-radius: var(--radius-base);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--duration-normal) ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(4, 120, 87, 0.3);
            font-family: inherit;
        }

        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.4);
        }

        .login-button:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: none;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .spinner.show {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            padding: 0 40px 30px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            background: white;
        }

        .login-footer a {
            color: #047857;
            text-decoration: none;
            font-weight: 600;
            transition: color var(--duration-fast) ease;
        }

        .login-footer a:hover {
            color: #065f46;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-body {
                padding: 35px 25px;
            }

            .login-header {
                padding: 40px 25px;
            }

            .login-footer {
                padding: 0 25px 25px;
            }

            .login-header h1 {
                font-size: 26px;
            }

            .login-logo {
                font-size: 40px;
                width: 70px;
                height: 70px;
            }

            .login-body h2 {
                font-size: 20px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="login-header-content">
                    <div class="login-logo">
                        <i class="fas fa-building"></i>
                    </div>
                    <h1>AppNotiDeudores</h1>
                    <p>Sistema de Notificación a Deudores</p>
                </div>
            </div>

            <!-- Body -->
            <div class="login-body">
                <h2>Bienvenido</h2>
                <p class="login-subtitle">Ingresa tus credenciales para acceder al sistema</p>

                <!-- Mensajes de alerta -->
                <div id="messageContainer"></div>

                <!-- Formulario -->
                <form id="loginForm">
                    <!-- Campo Usuario -->
                    <div class="form-group">
                        <label for="usuario">
                            <i class="fas fa-user"></i>
                            Usuario <span class="required">*</span>
                        </label>
                        <input type="text" id="usuario" placeholder="Ingresa tu usuario" required autocomplete="username">
                        <div class="error-message" id="error-usuario">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="form-group">
                        <label for="contrasena">
                            <i class="fas fa-lock"></i>
                            Contraseña <span class="required">*</span>
                        </label>
                        <input type="password" id="contrasena" placeholder="Ingresa tu contraseña" required
                            autocomplete="current-password">
                        <div class="error-message" id="error-contrasena">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <!-- Botón -->
                    <button type="submit" class="login-button" id="loginBtn">
                        <span class="spinner" id="spinner"></span>
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Ingresar</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                ¿Problemas para ingresar? <a href="#">Contacta soporte</a>
            </div>
        </div>
    </div>
    <script>
        const originalFetch = window.fetch;
        window.fetch = function (...args) {
            const token = localStorage.getItem('token');

            if (token) {
                let options = args[1] || {};
                options.headers = options.headers || {};
                options.headers['Authorization'] = `Bearer ${token}`;
                args[1] = options;
            }

            return originalFetch.apply(this, args);
        };
    </script>

@endsection

@section('scripts')
    <script>
        // Verificar si ya está autenticado (SOLO al cargar la página, sin verificar con API)
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('token');
            const usuarioJSON = localStorage.getItem('usuario');

            // Solo verificar si hay AMBOS: token Y usuario guardados
            if (token && usuarioJSON) {
                try {
                    const usuario = JSON.parse(usuarioJSON);

                    // Determinar redirección según el rol guardado
                    let redirectUrl = '/';

                    if (usuario.rol === 'administrador') {
                        console.log('Redirigiendo a administrador');
                        redirectUrl = '/admin/dashboard';
                    } else if (usuario.rol === 'institucion') {
                        redirectUrl = '/institution/dashboard';
                    }

                    // Redirigir sin hacer otra petición a la API
                    window.location.replace(redirectUrl);
                } catch (e) {
                    console.error('Error parsing usuario:', e);
                    localStorage.removeItem('token');
                    localStorage.removeItem('usuario');
                }
            }
        });


        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Limpiar errores previos
            limpiarErrores();
            document.getElementById('messageContainer').innerHTML = '';

            // Obtener valores
            const usuario = document.getElementById('usuario').value.trim();
            const contrasena = document.getElementById('contrasena').value.trim();

            // Mostrar spinner
            mostrarCargando(true);

            // Validación en cliente
            let valido = true;

            if (!usuario) {
                mostrarErrorCampo('usuario', 'El usuario es requerido');
                valido = false;
            } else if (usuario.length < 3) {
                mostrarErrorCampo('usuario', 'Mínimo 3 caracteres');
                valido = false;
            }

            if (!contrasena) {
                mostrarErrorCampo('contrasena', 'La contraseña es requerida');
                valido = false;
            } else if (contrasena.length < 3) {
                mostrarErrorCampo('contrasena', 'Mínimo 3 caracteres');
                valido = false;
            }

            if (!valido) {
                mostrarCargando(false);
                return;
            }

            try {
                // Llamar a la API
                const response = await API_CONFIG.call('auth/login', 'POST', {
                    usuario: usuario,
                    contrasena: contrasena
                });

                mostrarCargando(false);

                // Verificar respuesta exitosa
                if (response.success && response.data && response.data.token) {
                    // Guardar token y usuario
                    localStorage.setItem('token', response.data.token);
                    localStorage.setItem('usuario', JSON.stringify(response.data.user));

                    // Mostrar mensaje de éxito
                    mostrarMensaje('Ingreso exitoso. Redirigiendo...', 'success');

                    // Esperar un poco y redirigir según rol
                    setTimeout(() => {
                        const rol = response.data.user.rol;
                        if (rol === 'administrador') {
                            window.location.href = '/admin/dashboard';
                        } else if (rol === 'institucion') {
                            window.location.href = '/institution/dashboard';
                        } else {
                            window.location.href = '/';
                        }
                    }, 500);
                } else {
                    // Mensaje de error de la API
                    const mensaje = response.message || 'No se pudo procesar la solicitud';
                    mostrarMensaje(mensaje, 'error');
                }
            } catch (error) {
                mostrarCargando(false);
                console.error('Error:', error);

                // Clasificar el error
                let mensaje = 'Error de conexión con el servidor';
                if (error.message.includes('Failed to fetch')) {
                    mensaje = 'No se puede conectar. Verifica tu conexión.';
                } else if (error.message.includes('Network')) {
                    mensaje = 'Error de red. Intenta de nuevo.';
                }

                mostrarMensaje(mensaje, 'error');
            }
        });

        function limpiarErrores() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.querySelector('span').textContent = '';
            });
            document.querySelectorAll('input').forEach(el => el.classList.remove('error'));
        }

        function mostrarErrorCampo(campo, mensaje) {
            const input = document.getElementById(campo);
            const errorDiv = document.getElementById('error-' + campo);

            input.classList.add('error');
            errorDiv.querySelector('span').textContent = mensaje;
            errorDiv.classList.add('show');

            // Focus y scroll
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function mostrarMensaje(mensaje, tipo = 'error') {
            const container = document.getElementById('messageContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${tipo}`;
            alert.classList.add('show');

            const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            alert.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <span>${mensaje}</span>
                `;

            container.appendChild(alert);

            // Auto-desaparecer
            if (tipo !== 'error') {
                setTimeout(() => alert.remove(), 5000);
            }
        }

        function mostrarCargando(cargando) {
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('spinner');

            btn.disabled = cargando;

            if (cargando) {
                spinner.classList.add('show');
            } else {
                spinner.classList.remove('show');
            }
        }

        // Enter para enviar
        document.getElementById('contrasena').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });

        // Limpiar errores al escribir
        document.getElementById('usuario').addEventListener('input', function () {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                document.getElementById('error-usuario').classList.remove('show');
            }
        });

        document.getElementById('contrasena').addEventListener('input', function () {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                document.getElementById('error-contrasena').classList.remove('show');
            }
        });
    </script>
@endsection