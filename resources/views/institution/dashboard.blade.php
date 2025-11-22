@extends('layouts.app')

@section('title', 'Dashboard Institución - AppNotiDeudores')

@section('styles')
    <style>
        body {
            background: #f9fafb;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            padding: 30px 40px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(4, 120, 87, 0.2);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 28px;
            margin: 0;
            color: white;
            font-weight: 700;
        }

        .header-left p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .header-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-right: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }

        .user-details h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .user-details p {
            margin: 3px 0 0 0;
            font-size: 12px;
            opacity: 0.8;
        }

        /* Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 30px 20px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #047857;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .stat-card-icon {
            font-size: 32px;
            color: #047857;
            margin-bottom: 15px;
        }

        .stat-card-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .stat-card-meta {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 8px;
        }

        .stat-card.success { border-left-color: #10b981; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #ef4444; }
        .stat-card.info { border-left-color: #3b82f6; }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            border-color: #047857;
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.15);
            transform: translateX(5px);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #047857;
        }

        .action-content {
            flex: 1;
        }

        .action-content h3 {
            margin: 0 0 5px 0;
            font-size: 15px;
            color: #1f2937;
        }

        .action-content p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        .action-arrow {
            color: #d1d5db;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .action-card:hover .action-arrow {
            color: #047857;
            transform: translateX(3px);
        }

        /* Content Section */
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
            color: #1f2937;
        }

        .section-header h2 i {
            margin-right: 8px;
            color: #047857;
        }

        /* Buttons */
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

        .btn-primary:hover {
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn i {
            font-size: 14px;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid;
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
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-right {
                width: 100%;
                justify-content: center;
            }

            .user-info {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding-right: 0;
                padding-bottom: 15px;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-chart-line"></i> Dashboard Institucional</h1>
                <p id="institucionNombre">Bienvenido</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar" id="userAvatar">U</div>
                    <div class="user-details">
                        <h3 id="userName">Usuario</h3>
                        <p>Institución</p>
                    </div>
                </div>
                <button class="btn btn-danger btn-sm" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-container">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Estadísticas -->
        <div class="stats-grid" id="statsGrid" style="display: none;">
            <div class="stat-card success">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                <div class="stat-card-label">Clientes</div>
                <p class="stat-card-value" id="clientCount">0</p>
                <div class="stat-card-meta">Clientes registrados</div>
            </div>

            <div class="stat-card info">
                <div class="stat-card-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-card-label">Cuentas Totales</div>
                <p class="stat-card-value" id="debtCount">0</p>
                <div class="stat-card-meta">Cuentas registradas</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-card-label">Pendientes</div>
                <p class="stat-card-value" id="pendingCount">0</p>
                <div class="stat-card-meta">Deudas pendientes</div>
            </div>

            <div class="stat-card danger">
                <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-card-label">Vencidas</div>
                <p class="stat-card-value" id="overdueCount">0</p>
                <div class="stat-card-meta">Deudas vencidas</div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="content-section" style="margin-bottom: 30px;">
            <div class="section-header">
                <h2><i class="fas fa-lightning-bolt"></i> Acciones Rápidas</h2>
            </div>

            <div class="quick-actions">
                <a href="/institution/clientes/crear" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-content">
                        <h3>Nuevo Cliente</h3>
                        <p>Registrar un nuevo cliente en el sistema</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>

                <a href="/institution/clientes" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="action-content">
                        <h3>Ver Clientes</h3>
                        <p>Gestiona todos los clientes registrados</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>

                <a href="/institution/deudas/crear" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="action-content">
                        <h3>Nueva Deuda</h3>
                        <p>Registrar una nueva cuenta por cobrar</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>

                <a href="/institution/deudas" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="action-content">
                        <h3>Deudas</h3>
                        <p>Ver todas las deudas registradas</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Resumen de Clientes Recientes</h2>
                <button class="btn btn-secondary btn-sm" onclick="recargarDatos()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>

            <div id="dashboardContent">
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <p style="color: #6b7280;">Cargando información...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Verificar autenticación
        if (!localStorage.getItem('token')) {
            window.location.href = '/login';
        }

        let usuarioActual = JSON.parse(localStorage.getItem('usuario') || '{}');

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            mostrarUsuario();
            cargarDashboard();
        });

        function mostrarUsuario() {
            const avatar = document.getElementById('userAvatar');
            const nombre = document.getElementById('userName');
            const institucion = document.getElementById('institucionNombre');

            if (usuarioActual && usuarioActual.usuario) {
                const inicial = usuarioActual.usuario.charAt(0).toUpperCase();
                avatar.textContent = inicial;
                nombre.textContent = usuarioActual.usuario;
            }

            if (usuarioActual && usuarioActual.institucion) {
                institucion.textContent = 'Institución: ' + usuarioActual.institucion;
            }
        }

        async function cargarDashboard() {
            try {
                const data = await API_CONFIG.call('institution/dashboard', 'GET');

                if (data.success) {
                    actualizarEstadisticas(data.stats);
                    mostrarClientes(data.recent_clients);
                } else {
                    mostrarMensaje(data.message || 'Error al cargar el dashboard', 'error');
                    mostrarContenidoVacio();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('No se pudo conectar con el servidor', 'error');
                mostrarContenidoVacio();
            }
        }

        function actualizarEstadisticas(stats) {
            document.getElementById('statsGrid').style.display = 'grid';
            document.getElementById('clientCount').textContent = stats.clients || 0;
            document.getElementById('debtCount').textContent = stats.debts || 0;
            document.getElementById('pendingCount').textContent = stats.pending || 0;
            document.getElementById('overdueCount').textContent = stats.overdue || 0;
        }

        function mostrarClientes(clientes) {
            const container = document.getElementById('dashboardContent');

            if (!clientes || clientes.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 style="color: #6b7280; margin-bottom: 8px;">Sin clientes</h3>
                        <p style="color: #9ca3af; margin: 0;">No hay clientes registrados. <a href="/institution/clientes/crear" style="color: #047857; font-weight: 600;">Crear primer cliente</a></p>
                    </div>
                `;
                return;
            }

            let html = `
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 12px; color: #6b7280; text-transform: uppercase;">Nombre</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 12px; color: #6b7280; text-transform: uppercase;">Teléfono</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 12px; color: #6b7280; text-transform: uppercase;">Correo</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 12px; color: #6b7280; text-transform: uppercase;">Deudas</th>
                                <th style="padding: 12px 16px; text-align: left; font-weight: 600; font-size: 12px; color: #6b7280; text-transform: uppercase;">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            clientes.forEach(cliente => {
                html += `
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-size: 14px; color: #1f2937;"><strong>${cliente.nombre || 'N/A'}</strong></td>
                        <td style="padding: 16px; font-size: 14px; color: #1f2937;">${cliente.telefono || 'N/A'}</td>
                        <td style="padding: 16px; font-size: 14px; color: #1f2937;">${cliente.correo || 'N/A'}</td>
                        <td style="padding: 16px; font-size: 14px; color: #1f2937;">${cliente.deuda_count || 0}</td>
                        <td style="padding: 16px; font-size: 14px; color: #1f2937;">$${parseFloat(cliente.deuda_total || 0).toLocaleString('es-CO', { minimumFractionDigits: 2 })}</td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            container.innerHTML = html;
        }

        function mostrarContenidoVacio() {
            const container = document.getElementById('dashboardContent');
            container.innerHTML = `
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 style="color: #6b7280; margin-bottom: 8px;">Error al cargar</h3>
                    <p style="color: #9ca3af; margin: 0;">No se pudo cargar la información del dashboard</p>
                </div>
            `;
        }

        function recargarDatos() {
            const container = document.getElementById('dashboardContent');
            container.innerHTML = `
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 40px; color: #047857; margin-bottom: 15px;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <p style="color: #6b7280;">Actualizando...</p>
                </div>
            `;
            cargarDashboard();
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

        function logout() {
            if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
                localStorage.removeItem('token');
                localStorage.removeItem('usuario');
                window.location.href = '/login';
            }
        }
    </script>
@endsection
