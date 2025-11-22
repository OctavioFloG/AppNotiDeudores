@extends('layouts.app')

@section('title', 'Registrar Cliente - AppNotiDeudores')

@section('styles')
    <style>
        body {
            background: #f9fafb;
        }

        .page-header {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            padding: 30px 40px;
            margin-bottom: 30px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .page-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .container-main {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px 30px 20px;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .form-section {
            margin-bottom: 40px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title i {
            color: #047857;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
        }

        .required {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: white;
            color: #1f2937;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #d1d5db;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
        }

        .form-group input.error,
        .form-group textarea.error {
            border-color: #ef4444;
            background-color: #fef5f5;
        }

        .form-help {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
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

        /* Botones */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #f3f4f6;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(4, 120, 87, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #1f2937;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn:disabled {
            opacity: 0.6;
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

        /* Alertas */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }

        .alert.show {
            display: flex;
            align-items: center;
            gap: 10px;
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

        @media (max-width: 768px) {
            .form-card {
                padding: 25px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Registrar Cliente</h1>
        <p>Completa el formulario para agregar un nuevo cliente</p>
    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Formulario -->
        <div class="form-card">
            <form id="clientForm">
                <!-- Sección de Información Básica -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-info-circle"></i>
                        Información del Cliente
                    </h2>

                    <div class="form-group">
                        <label for="nombre">
                            Nombre Completo <span class="required">*</span>
                        </label>
                        <input type="text" id="nombre" placeholder="Ej: Juan Pérez García" required>
                        <div class="error-message" id="error-nombre">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>
                </div>

                <!-- Sección de Contacto -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-phone"></i>
                        Información de Contacto
                    </h2>

                    <div class="form-group">
                        <label for="telefono">
                            Teléfono <span class="required">*</span>
                        </label>
                        <input type="tel" id="telefono" placeholder="Ej: +57 300 1234567" required>
                        <div class="form-help">
                            Formatos aceptados: +57 300 1234567, +573001234567, 3001234567
                        </div>
                        <div class="error-message" id="error-telefono">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correo">
                            Correo Electrónico <span class="required">*</span>
                        </label>
                        <input type="email" id="correo" placeholder="Ej: juan@example.com" required>
                        <div class="error-message" id="error-correo">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner" id="spinner"></span>
                        <i class="fas fa-save"></i>
                        <span>Registrar Cliente</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Verificar autenticación
        if (!localStorage.getItem('token')) {
            window.location.href = '/login';
        }

        document.getElementById('clientForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Limpiar errores
            limpiarErrores();
            document.getElementById('messageContainer').innerHTML = '';

            // Obtener valores
            const datos = {
                nombre: document.getElementById('nombre').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                correo: document.getElementById('correo').value.trim(),
            };

            // Validación
            let valido = true;

            // Validar nombre
            if (!datos.nombre) {
                mostrarErrorCampo('nombre', 'El nombre es requerido');
                valido = false;
            } else if (datos.nombre.length < 3) {
                mostrarErrorCampo('nombre', 'El nombre debe tener al menos 3 caracteres');
                valido = false;
            } else if (datos.nombre.length > 255) {
                mostrarErrorCampo('nombre', 'El nombre no puede exceder 255 caracteres');
                valido = false;
            }

            // Validar teléfono
            if (!datos.telefono) {
                mostrarErrorCampo('telefono', 'El teléfono es requerido');
                valido = false;
            } else if (!validarTelefono(datos.telefono)) {
                mostrarErrorCampo('telefono', 'Formato de teléfono inválido. Use: +57 300 1234567');
                valido = false;
            }

            // Validar correo
            if (!datos.correo) {
                mostrarErrorCampo('correo', 'El correo es requerido');
                valido = false;
            } else if (!validarEmail(datos.correo)) {
                mostrarErrorCampo('correo', 'Correo inválido');
                valido = false;
            }

            if (!valido) return;

            mostrarCargando(true);

            try {
                const response = await API_CONFIG.call('institution/clientes', 'POST', datos);

                mostrarCargando(false);

                if (response.success) {
                    mostrarMensaje('Cliente registrado exitosamente. Redirigiendo...', 'success');

                    setTimeout(() => {
                        window.location.href = '/institution/clientes';
                    }, 1500);
                } else {
                    const errors = response.errors;
                    if (errors) {
                        if (errors.nombre) mostrarErrorCampo('nombre', errors.nombre[0]);
                        if (errors.correo) mostrarErrorCampo('correo', errors.correo[0]);
                        if (errors.telefono) mostrarErrorCampo('telefono', errors.telefono[0]);
                    }
                    mostrarMensaje(response.message || 'Error al registrar', 'error');
                }
            } catch (error) {
                mostrarCargando(false);
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
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
            input.focus();
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

            if (tipo !== 'error') {
                setTimeout(() => alert.remove(), 5000);
            }
        }

        function mostrarCargando(cargando) {
            const btn = document.getElementById('submitBtn');
            const spinner = document.getElementById('spinner');

            btn.disabled = cargando;

            if (cargando) {
                spinner.classList.add('show');
            } else {
                spinner.classList.remove('show');
            }
        }

        function validarEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function validarTelefono(telefono) {
            // Acepta formatos: +57 300 1234567, +573001234567, 3001234567, 300 123 4567
            const regex = /^(\+\d{1,3}\s?)?(\d{1,4}\s?)*\d{1,4}$/;
            // Debe tener entre 7 y 15 dígitos
            const soloDigitos = telefono.replace(/\D/g, '');
            return regex.test(telefono) && soloDigitos.length >= 7 && soloDigitos.length <= 15;
        }
    </script>
@endsection
