@extends('layouts.app')

@section('title', 'Registrar Deuda - AppNotiDeudores')

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

        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #047857;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #065f46;
        }

        .info-box i {
            margin-right: 8px;
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
        <h1><i class="fas fa-file-invoice-dollar"></i> Registrar Deuda</h1>
        <p>Crea una nueva cuenta por cobrar para un cliente</p>
    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Formulario -->
        <div class="form-card">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                El estado de la deuda será automáticamente "Pendiente"
            </div>

            <form id="debtForm">
                <!-- Sección de Cliente -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-user"></i>
                        Seleccionar Cliente
                    </h2>

                    <div class="form-group">
                        <label for="clientId">
                            Cliente <span class="required">*</span>
                        </label>
                        <select id="clientId" required>
                            <option value="">-- Selecciona un cliente --</option>
                        </select>
                        <div class="error-message" id="error-clientId">
                            <i class="fas fa-exclamation-circle"></i>
                            <span></span>
                        </div>
                    </div>
                </div>

                <!-- Sección de Información de Deuda -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-dollar-sign"></i>
                        Información de la Deuda
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="monto">
                                Monto <span class="required">*</span>
                            </label>
                            <input type="number" id="monto" placeholder="0.00" step="0.01" min="0" required>
                            <div class="error-message" id="error-monto">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">
                                Descripción <span class="required">*</span>
                            </label>
                            <input type="text" id="descripcion" placeholder="Ej: Matrícula semestre 2025-1" required>
                            <div class="error-message" id="error-descripcion">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Fechas -->
                <div class="form-section">
                    <h2 class="form-section-title">
                        <i class="fas fa-calendar"></i>
                        Fechas
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fechaEmision">
                                Fecha de Emisión <span class="required">*</span>
                            </label>
                            <input type="date" id="fechaEmision" required>
                            <div class="form-help">Fecha en que se registra la deuda</div>
                            <div class="error-message" id="error-fechaEmision">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fechaVencimiento">
                                Fecha de Vencimiento <span class="required">*</span>
                            </label>
                            <input type="date" id="fechaVencimiento" required>
                            <div class="form-help">Fecha límite para el pago</div>
                            <div class="error-message" id="error-fechaVencimiento">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner" id="spinner"></span>
                        <i class="fas fa-save"></i>
                        <span>Registrar Deuda</span>
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

        let clientes = [];

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            cargarClientes();
            establecerFechaHoy();

            document.getElementById('debtForm').addEventListener('submit', registrarDeuda);
        });

        function establecerFechaHoy() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('fechaEmision').value = hoy;
        }

        async function cargarClientes() {
            try {
                const response = await API_CONFIG.call('institution/clientes', 'GET');

                if (response.success) {
                    clientes = response.data || [];
                    llenarSelectClientes();
                } else {
                    mostrarMensaje('Error al cargar clientes', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
            }
        }

        function llenarSelectClientes() {
            const select = document.getElementById('clientId');
            let html = '<option value="">-- Selecciona un cliente --</option>';

            clientes.forEach(cliente => {
                html += `<option value="${cliente.id_cliente}">${cliente.nombre} (${cliente.correo})</option>`;
            });

            select.innerHTML = html;
        }

        async function registrarDeuda(e) {
            e.preventDefault();

            // Limpiar errores
            limpiarErrores();
            document.getElementById('messageContainer').innerHTML = '';

            // Obtener valores
            const datos = {
                id_cliente: document.getElementById('clientId').value,
                monto: document.getElementById('monto').value,
                descripcion: document.getElementById('descripcion').value.trim(),
                fecha_emision: document.getElementById('fechaEmision').value,
                fecha_vencimiento: document.getElementById('fechaVencimiento').value,
            };

            // Validación
            let valido = true;

            if (!datos.id_cliente) {
                mostrarErrorCampo('clientId', 'Debes seleccionar un cliente');
                valido = false;
            }

            if (!datos.monto || parseFloat(datos.monto) <= 0) {
                mostrarErrorCampo('monto', 'El monto debe ser mayor a 0');
                valido = false;
            }

            if (!datos.descripcion) {
                mostrarErrorCampo('descripcion', 'La descripción es requerida');
                valido = false;
            } else if (datos.descripcion.length < 3) {
                mostrarErrorCampo('descripcion', 'La descripción debe tener al menos 3 caracteres');
                valido = false;
            }

            if (!datos.fecha_emision) {
                mostrarErrorCampo('fechaEmision', 'La fecha de emisión es requerida');
                valido = false;
            }

            if (!datos.fecha_vencimiento) {
                mostrarErrorCampo('fechaVencimiento', 'La fecha de vencimiento es requerida');
                valido = false;
            } else {
                const fechaEmision = new Date(datos.fecha_emision);
                const fechaVencimiento = new Date(datos.fecha_vencimiento);

                if (fechaVencimiento <= fechaEmision) {
                    mostrarErrorCampo('fechaVencimiento', 'La fecha de vencimiento debe ser posterior a la emisión');
                    valido = false;
                }
            }

            if (!valido) return;

            mostrarCargando(true);

            try {
                const response = await API_CONFIG.call('institution/deudas', 'POST', datos);

                mostrarCargando(false);

                if (response.success) {
                    mostrarMensaje('Deuda registrada exitosamente. Redirigiendo...', 'success');

                    setTimeout(() => {
                        window.location.href = '/institution/deudas';
                    }, 1500);
                } else {
                    const errors = response.errors;
                    if (errors) {
                        if (errors.id_cliente) mostrarErrorCampo('clientId', errors.id_cliente[0]);
                        if (errors.monto) mostrarErrorCampo('monto', errors.monto[0]);
                        if (errors.descripcion) mostrarErrorCampo('descripcion', errors.descripcion[0]);
                        if (errors.fecha_emision) mostrarErrorCampo('fechaEmision', errors.fecha_emision[0]);
                        if (errors.fecha_vencimiento) mostrarErrorCampo('fechaVencimiento', errors.fecha_vencimiento[0]);
                    }
                    mostrarMensaje(response.message || 'Error al registrar', 'error');
                }
            } catch (error) {
                mostrarCargando(false);
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
            }
        }

        function limpiarErrores() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.querySelector('span').textContent = '';
            });
            document.querySelectorAll('input, select').forEach(el => el.classList.remove('error'));
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
    </script>
@endsection
