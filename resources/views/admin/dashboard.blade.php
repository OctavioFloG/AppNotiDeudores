@extends('layouts.app')

@section('title', 'Dashboard Admin - AppNotiDeudores')

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

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }

        th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        td {
            padding: 16px;
            font-size: 14px;
            color: #1f2937;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #9ca3af;
            margin: 0;
        }

        /* Loading */
        .loading-container {
            text-align: center;
            padding: 60px 20px;
        }

        .spinner-border {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top-color: #047857;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-container p {
            color: #6b7280;
            margin-top: 15px;
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

        /* Badges */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
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

            th, td {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-chart-line"></i> Dashboard Administrativo</h1>
                <p>Bienvenido al panel de control</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar" id="userAvatar">A</div>
                    <div class="user-details">
                        <h3 id="userName">Administrador</h3>
                        <p>Sistema</p>
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
                <div class="stat-card-icon"><i class="fas fa-building"></i></div>
                <div class="stat-card-label">Instituciones</div>
                <p class="stat-card-value" id="institutionCount">0</p>
                <div class="stat-card-meta">Instituciones activas</div>
            </div>

            <div class="stat-card info">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                <div class="stat-card-label">Usuarios</div>
                <p class="stat-card-value" id="userCount">0</p>
                <div class="stat-card-meta">Usuarios del sistema</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-card-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-card-label">Cuentas por Cobrar</div>
                <p class="stat-card-value" id="debtCount">0</p>
                <div class="stat-card-meta">Deudas registradas</div>
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
                <a href="/admin/institutions/create" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="action-content">
                        <h3>Nueva Institución</h3>
                        <p>Registrar una nueva institución en el sistema</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="action-content">
                        <h3>Nuevo Usuario</h3>
                        <p>Crear un nuevo usuario en el sistema</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>

                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="action-content">
                        <h3>Nueva Deuda</h3>
                        <p>Registrar una nueva cuenta por cobrar</p>
                    </div>
                    <i class="fas fa-chevron-right action-arrow"></i>
                </a>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Información del Sistema</h2>
                <button class="btn btn-secondary btn-sm" onclick="recargarDatos()">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>

            <div id="dashboardContent">
                <div class="loading-container">
                    <div class="spinner-border"></div>
                    <p>Cargando información...</p>
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

            if (usuarioActual && usuarioActual.usuario) {
                const inicial = usuarioActual.usuario.charAt(0).toUpperCase();
                avatar.textContent = inicial;
                nombre.textContent = usuarioActual.usuario;
            }
        }

        async function cargarDashboard() {
            try {
                const data = await API_CONFIG.call('admin/dashboard', 'GET');

                if (data.success) {
                    actualizarEstadisticas(data.stats);
                    mostrarDeudas(data.recent_debts);
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
            document.getElementById('institutionCount').textContent = stats.institutions || 0;
            document.getElementById('userCount').textContent = stats.users || 0;
            document.getElementById('debtCount').textContent = stats.debts || 0;
            document.getElementById('overdueCount').textContent = stats.overdue || 0;
        }

        function mostrarDeudas(deudas) {
            const container = document.getElementById('dashboardContent');

            if (!deudas || deudas.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>Sin datos</h3>
                        <p>No hay deudas registradas en el sistema</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Institución</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Vencimiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            deudas.forEach(deuda => {
                const estado = deuda.estado || 'pendiente';
                const badge = estado === 'pagado' ? 'badge-success' :
                    (new Date(deuda.fecha_vencimiento) < new Date() ? 'badge-danger' : 'badge-warning');

                html += `
                    <tr>
                        <td>#${deuda.id}</td>
                        <td>${deuda.institution?.nombre || 'N/A'}</td>
                        <td>${deuda.client?.nombre || 'N/A'}</td>
                        <td>$${parseFloat(deuda.monto).toLocaleString('es-CO', { minimumFractionDigits: 2 })}</td>
                        <td>${formatDate(deuda.fecha_vencimiento)}</td>
                        <td><span class="badge ${badge}">${estado}</span></td>
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
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3>Error al cargar</h3>
                    <p>No se pudo cargar la información del dashboard</p>
                </div>
            `;
        }

        function recargarDatos() {
            const container = document.getElementById('dashboardContent');
            container.innerHTML = `
                <div class="loading-container">
                    <div class="spinner-border"></div>
                    <p>Actualizando...</p>
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

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-CO', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
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
