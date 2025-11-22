@extends('layouts.app')

@section('title', 'Registrar Institución - AppNotiDeudores')

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
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            margin-bottom: 0;
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
            border-radius: var(--radius-base);
            font-size: 14px;
            transition: all var(--duration-normal) ease;
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

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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

        /* Criterios de aceptación */
        .acceptance-criteria {
            background: #f3f4f6;
            border-left: 4px solid #047857;
            padding: 20px;
            border-radius: var(--radius-base);
            margin-top: 30px;
        }

        .acceptance-criteria h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .acceptance-criteria ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .acceptance-criteria li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #4b5563;
        }

        .acceptance-criteria li:last-child {
            margin-bottom: 0;
        }

        .acceptance-criteria li i {
            color: #047857;
            margin-top: 3px;
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
            border-radius: var(--radius-base);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--duration-normal) ease;
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
            border-radius: var(--radius-base);
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
            border-left: 4px solid;
            animation: slideIn var(--duration-normal) ease;
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
            .form-row {
                grid-template-columns: 1fr;
            }

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
        <h1><i class="fas fa-building"></i> Registrar Institución</h1>
        <p>Completa el formulario para agregar una nueva institución al sistema</p>
    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Formulario -->
        <div class="form-card">
            <form id="institutionForm">
                <!-- Sección de Información Básica -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-info-circle"></i>
                        Información Básica
                    </h2>

                    <div class="form-group">
                        <label for="nombre">
                            Nombre de la Institución <span class="required">*</span>
                        </label>
                        <input type="text" id="nombre" placeholder="Ej: Instituto Educativo San José" required>
                        <div class="error-message" id="error-nombre">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                        <div class="form-help">El nombre completo de la institución</div>
                    </div>
                </div>

                <!-- Sección de Contacto -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-phone"></i>
                        Información de Contacto
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="correo">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input type="correo" id="correo" placeholder="Ej: info@institucion.com" required>
                            <div class="error-message" id="error-correo">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telefono">
                                Teléfono <span class="required">*</span>
                            </label>
                            <input type="tel" id="telefono" placeholder="Ej: +57 1 234 5678" required>
                            <div class="error-message" id="error-telefono">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Dirección -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Dirección
                    </h2>

                    <div class="form-group">
                        <label for="direccion">
                            Dirección <span class="required">*</span>
                        </label>
                        <input type="text" id="direccion" placeholder="Ej: Calle 123 #45-67" required>
                        <div class="error-message" id="error-direccion">
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
                        <span>Registrar Institución</span>
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

    document.getElementById('institutionForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        // Limpiar errores
        limpiarErrores();
        document.getElementById('messageContainer').innerHTML = '';

        // Obtener valores
        const datos = {
            nombre: document.getElementById('nombre').value.trim(),
            correo: document.getElementById('correo').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            direccion: document.getElementById('direccion').value.trim(),
        };

        // Validación
        let valido = true;

        if (!datos.nombre) {
            mostrarErrorCampo('nombre', 'El nombre es requerido');
            valido = false;
        }

        if (!datos.direccion) {
            mostrarErrorCampo('direccion', 'La dirección es requerida');
            valido = false;
        }

        if (!datos.correo) {
            mostrarErrorCampo('correo', 'El correo es requerido');
            valido = false;
        } else if (!validarEmail(datos.correo)) {
            mostrarErrorCampo('correo', 'Correo inválido');
            valido = false;
        }

        if (!datos.telefono) {
            mostrarErrorCampo('telefono', 'El teléfono es requerido');
            valido = false;
        }

        if (!valido) return;

        mostrarCargando(true);

        try {
            const response = await API_CONFIG.call('institutions', 'POST', datos);

            mostrarCargando(false);

            if (response.success) {
                // Mostrar modal con credenciales
                mostrarCredenciales(response.data);
            } else {
                const errors = response.errors;
                if (errors) {
                    if (errors.nombre) mostrarErrorCampo('nombre', errors.nombre[0]);
                    if (errors.correo) mostrarErrorCampo('correo', errors.correo[0]);
                    if (errors.telefono) mostrarErrorCampo('telefono', errors.telefono[0]);
                    if (errors.direccion) mostrarErrorCampo('direccion', errors.direccion[0]);
                }
                mostrarMensaje(response.message || 'Error al registrar', 'error');
            }
        } catch (error) {
            mostrarCargando(false);
            console.error('Error:', error);
            mostrarMensaje('Error de conexión', 'error');
        }
    });

    function mostrarCredenciales(data) {
        const institution = data.institution;
        const credentials = data.credentials;

        // Crear modal
        const modal = document.createElement('div');
        modal.id = 'credentialsModal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        `;

        modal.innerHTML = `
            <div style="
                background: white;
                border-radius: 12px;
                padding: 40px;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: slideDown 0.3s ease;
            ">
                <!-- Icono de éxito -->
                <div style="
                    text-align: center;
                    margin-bottom: 30px;
                ">
                    <div style="
                        width: 60px;
                        height: 60px;
                        background: #d1fae5;
                        border-radius: 50%;
                        margin: 0 auto 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 30px;
                    ">
                        <i class="fas fa-check" style="color: #047857;"></i>
                    </div>
                    <h2 style="margin: 0 0 10px 0; color: #1f2937; font-size: 24px;">Institución Registrada</h2>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">Las credenciales han sido generadas automáticamente</p>
                </div>

                <!-- Información de la institución -->
                <div style="
                    background: #f9fafb;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                ">
                    <h3 style="margin: 0 0 15px 0; color: #1f2937; font-weight: 600;">Datos de la Institución</h3>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">Nombre</label>
                        <p style="margin: 0; color: #1f2937; font-size: 14px;">${institution.nombre}</p>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">Correo</label>
                        <p style="margin: 0; color: #1f2937; font-size: 14px;">${institution.correo}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; color: #6b7280; font-weight: 600; margin-bottom: 4px;">Teléfono</label>
                        <p style="margin: 0; color: #1f2937; font-size: 14px;">${institution.telefono}</p>
                    </div>
                </div>

                <!-- Credenciales -->
                <div style="
                    background: #fef3c7;
                    border: 2px solid #fcd34d;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 25px;
                ">
                    <h3 style="margin: 0 0 15px 0; color: #92400e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-lock"></i> Credenciales de Acceso
                    </h3>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 12px; color: #92400e; font-weight: 600; margin-bottom: 6px;">Usuario</label>
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        ">
                            <input type="text" id="usuarioInput" value="${credentials.usuario}" readonly style="
                                flex: 1;
                                padding: 10px;
                                border: 1px solid #fcd34d;
                                border-radius: 6px;
                                font-family: monospace;
                                font-size: 13px;
                                color: #1f2937;
                                background: white;
                            ">
                            <button onclick="copiarAlPortapapeles('usuarioInput')" style="
                                padding: 10px 12px;
                                background: #047857;
                                color: white;
                                border: none;
                                border-radius: 6px;
                                cursor: pointer;
                                font-weight: 600;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.background='#065f46'" onmouseout="this.style.background='#047857'">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; color: #92400e; font-weight: 600; margin-bottom: 6px;">Contraseña</label>
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        ">
                            <input type="text" id="contrasenaInput" value="${credentials.contrasena}" readonly style="
                                flex: 1;
                                padding: 10px;
                                border: 1px solid #fcd34d;
                                border-radius: 6px;
                                font-family: monospace;
                                font-size: 13px;
                                color: #1f2937;
                                background: white;
                            ">
                            <button onclick="copiarAlPortapapeles('contrasenaInput')" style="
                                padding: 10px 12px;
                                background: #047857;
                                color: white;
                                border: none;
                                border-radius: 6px;
                                cursor: pointer;
                                font-weight: 600;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.background='#065f46'" onmouseout="this.style.background='#047857'">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </div>
                    </div>
                    <p style="
                        margin: 15px 0 0 0;
                        font-size: 12px;
                        color: #92400e;
                        font-weight: 600;
                    ">
                        <i class="fas fa-exclamation-triangle"></i> Guarda estas credenciales. No se mostrarán de nuevo.
                    </p>
                </div>

                <!-- Botones -->
                <div style="
                    display: flex;
                    gap: 12px;
                ">
                    <button onclick="descargarCredenciales('${institution.nombre}', '${credentials.usuario}', '${credentials.contrasena}')" style="
                        flex: 1;
                        padding: 12px;
                        background: #047857;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='#065f46'" onmouseout="this.style.background='#047857'">
                        <i class="fas fa-download"></i> Descargar
                    </button>
                    <button onclick="irAlListado()" style="
                        flex: 1;
                        padding: 12px;
                        background: #f3f4f6;
                        color: #1f2937;
                        border: none;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="fas fa-list"></i> Ir al listado
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Agregar animación
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }

    function copiarAlPortapapeles(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        document.execCommand('copy');
        
        // Mostrar feedback
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copiado';
        setTimeout(() => {
            btn.innerHTML = original;
        }, 2000);
    }

    function descargarCredenciales(nombre, usuario, contrasena) {
        const texto = `Credenciales de Institución\n\nNombre: ${nombre}\nUsuario: ${usuario}\nContraseña: ${contrasena}\n\nGuarda estas credenciales en un lugar seguro.`;
        
        const blob = new Blob([texto], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `credenciales-${nombre.replace(/\s+/g, '-')}.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    function irAlListado() {
        window.location.href = '/admin/institutions';
    }

    function limpiarErrores() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.remove('show');
            el.querySelector('span').textContent = '';
        });
        document.querySelectorAll('input, textarea').forEach(el => el.classList.remove('error'));
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
</script>
@endsection
