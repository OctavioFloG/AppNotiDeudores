@extends('layouts.app')

@section('title', 'Detalles del Cliente - AppNotiDeudores')

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
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 30px 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #047857;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: gap 0.2s;
        }

        .back-link:hover {
            gap: 12px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: #047857;
            font-size: 20px;
        }

        /* Información del cliente */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #047857;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }

        /* Filtros */
        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }

        .filter-btn:hover {
            border-color: #047857;
            color: #047857;
        }

        .filter-btn.active {
            background: #047857;
            border-color: #047857;
            color: white;
        }

        /* Tabla */
        .table-container {
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
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        td {
            padding: 16px;
            font-size: 14px;
            color: #1f2937;
        }

        .monto {
            font-weight: 600;
            color: #047857;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-pagado {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-vencido {
            background: #fee2e2;
            color: #991b1b;
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
        .loading {
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
            to {
                transform: rotate(360deg);
            }
        }

        /* Estadísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dbeafe 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #047857;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #047857;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #1f2937;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
            }

            .filter-btn {
                width: 100%;
                justify-content: center;
            }

            th,
            td {
                padding: 12px 8px;
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-user"></i>
            <span id="clientName">Detalles del Cliente</span>
        </h1>
        <p>Historial completo de deudas</p>
    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Back Link -->
        <a href="/institution/clientes" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Volver a clientes
        </a>

        <!-- Información del Cliente -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-info-circle"></i>
                Información del Cliente
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nombre</div>
                    <div class="info-value" id="infoNombre">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Correo</div>
                    <div class="info-value" id="infoCorreo">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value" id="infoTelefono">-</div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-chart-bar"></i>
                Resumen de Deudas
            </h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="totalDeudas">0</div>
                    <div class="stat-label">Total de Deudas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="deudaPendiente">$0</div>
                    <div class="stat-label">Monto Pendiente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="deudaPagada">$0</div>
                    <div class="stat-label">Monto Pagado</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="deudaVencida">$0</div>
                    <div class="stat-label">Monto Vencido</div>
                </div>
            </div>
        </div>

        <!-- Filtros y Deudas -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-list"></i>
                Historial de Deudas
            </h2>

            <!-- Filtros -->
            <div class="filters">
                <button class="filter-btn active" onclick="filtrarDeudas('todos')">
                    <i class="fas fa-list"></i> Todas
                </button>
                <button class="filter-btn" onclick="filtrarDeudas('Pendiente')">
                    <i class="fas fa-clock"></i> Pendientes
                </button>
                <button class="filter-btn" onclick="filtrarDeudas('Pagada')">
                    <i class="fas fa-check"></i> Pagadas
                </button>
                <button class="filter-btn" onclick="filtrarDeudas('Vencida')">
                    <i class="fas fa-times"></i> Vencidas
                </button>
            </div>

            <!-- Tabla de deudas -->
            <div id="deudasContainer">
                <div class="loading">
                    <div class="spinner-border"></div>
                    <p style="color: #6b7280; margin-top: 15px;">Cargando deudas...</p>
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

        let clienteId = null;
        let deudasCompletas = [];
        let filtroActual = 'todos';

        // Obtener cliente_id de la URL
        document.addEventListener('DOMContentLoaded', function () {
            const pathSegments = window.location.pathname.split('/');
            clienteId = pathSegments[pathSegments.length - 1];

            if (!clienteId || isNaN(clienteId)) {
                alert('Cliente no válido');
                window.location.href = '/institution/clientes';
            }

            cargarDatos();
        });

        async function cargarDatos() {
            try {
                // Cargar deudas del cliente
                const response = await API_CONFIG.call(`institution/deudas`, 'GET');

                if (response.success) {
                    // Filtrar deudas por cliente
                    deudasCompletas = response.data.filter(deuda => deuda.id_cliente == clienteId);

                    // Cargar información del cliente (si está en las deudas)
                    if (deudasCompletas.length > 0) {
                        const cliente = deudasCompletas[0].client;
                        if (cliente) {
                            llenarInfoCliente(cliente);
                        }
                    }

                    calcularEstadisticas();
                    mostrarDeudas();
                } else {
                    alert(response.message || 'Error al cargar deudas');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        }

        function llenarInfoCliente(cliente) {
            document.getElementById('clientName').textContent = cliente.nombre;
            document.getElementById('infoNombre').textContent = cliente.nombre;
            document.getElementById('infoCorreo').textContent = cliente.correo;
            document.getElementById('infoTelefono').textContent = cliente.telefono;
        }

        function calcularEstadisticas() {
            let totalDeudas = 0;
            let montoPendiente = 0;
            let montoPagado = 0;
            let montoVencido = 0;
            const hoy = new Date();

            deudasCompletas.forEach(deuda => {
                const monto = parseFloat(deuda.monto);
                totalDeudas++;

                const fechaVencimiento = new Date(deuda.fecha_vencimiento);

                if (deuda.estado === 'Pagada') {
                    montoPagado += monto;
                } else if (fechaVencimiento < hoy) {
                    montoVencido += monto;
                } else {
                    montoPendiente += monto;
                }
            });

            document.getElementById('totalDeudas').textContent = totalDeudas;
            document.getElementById('deudaPendiente').textContent =
                `$${montoPendiente.toLocaleString('es-CO', { minimumFractionDigits: 2 })}`;
            document.getElementById('deudaPagada').textContent =
                `$${montoPagado.toLocaleString('es-CO', { minimumFractionDigits: 2 })}`;
            document.getElementById('deudaVencida').textContent =
                `$${montoVencido.toLocaleString('es-CO', { minimumFractionDigits: 2 })}`;
        }

        function filtrarDeudas(filtro) {
            filtroActual = filtro;

            // Actualizar botones activos
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.filter-btn').classList.add('active');

            mostrarDeudas();
        }

        function mostrarDeudas() {
            let deudasFiltradas = deudasCompletas;
            const hoy = new Date();

            if (filtroActual !== 'todos') {
                if (filtroActual === 'Vencida') {
                    deudasFiltradas = deudasCompletas.filter(deuda => {
                        const fechaVencimiento = new Date(deuda.fecha_vencimiento);
                        return deuda.estado === 'Pendiente' && fechaVencimiento < hoy;
                    });
                } else {
                    deudasFiltradas = deudasCompletas.filter(deuda => deuda.estado === filtroActual);
                }
            }

            if (deudasFiltradas.length === 0) {
                document.getElementById('deudasContainer').innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3>Sin deudas</h3>
                            <p>No hay deudas que coincidan con el filtro seleccionado</p>
                        </div>
                    `;
                return;
            }

            let html = `
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Emisión</th>
                                    <th>Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

            deudasFiltradas.forEach(deuda => {
                const monto = parseFloat(deuda.monto).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                const fechaEmision = formatDate(deuda.fecha_emision);
                const fechaVencimiento = formatDate(deuda.fecha_vencimiento);
                const fechaPago = deuda.fecha_pago ? formatDate(deuda.fecha_pago) : '-';
                const estadoBadge = getEstadoBadge(deuda.estado, deuda.fecha_vencimiento);

                html += `
                    <tr>
                        <td>${deuda.descripcion}</td>
                        <td><span class="monto">$${monto}</span></td>
                        <td>${fechaEmision}</td>
                        <td>${fechaVencimiento}</td>
                        <td>${estadoBadge}</td>
                        <td>${fechaPago}</td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                `;

            document.getElementById('deudasContainer').innerHTML = html;
        }

        function getEstadoBadge(estadoOriginal, fechaVencimientoRaw) {
            const estado = (estadoOriginal || '').toLowerCase();

            if (estado === 'pagada') {
                return '<span class="badge badge-pagada">Pagada</span>';
            }

            if (!fechaVencimientoRaw) {
                return '<span class="badge badge-pendiente">Pendiente</span>';
            }

            const d = new Date(fechaVencimientoRaw);
            if (isNaN(d.getTime())) {
                return '<span class="badge badge-pendiente">Pendiente</span>';
            }

            const hoy = new Date();
            hoy.setHours(0,0,0,0);
            d.setHours(0,0,0,0);

            if (d < hoy) {
                return '<span class="badge badge-vencido">Vencida</span>';
            }
            return '<span class="badge badge-pendiente">Pendiente</span>';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const d = new Date(dateString);
            if (isNaN(d.getTime())) return '-';
            return d.toLocaleDateString('es-CO', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

    </script>
@endsection