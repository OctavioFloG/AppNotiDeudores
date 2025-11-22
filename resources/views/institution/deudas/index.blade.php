@extends('layouts.app')

@section('title', 'Deudas - AppNotiDeudores')

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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .page-header-left h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .page-header-left p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 30px 20px;
        }

        /* Card Container */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header con búsqueda */
        .card-header {
            padding: 25px;
            border-bottom: 2px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card-header h2 {
            margin: 0;
            font-size: 20px;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header h2 i {
            color: #047857;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-nav {
            background: white;
            color: #047857;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
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

        /* Table */
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

        .debt-client {
            font-weight: 600;
            color: #047857;
        }

        .debt-amount {
            font-weight: 600;
            color: #1f2937;
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

        .badge-pagada {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-vencido {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Acciones */
        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-family: inherit;
        }

        .action-pay {
            background: #dbeafe;
            color: #0369a1;
        }

        .action-pay:hover {
            background: #bfdbfe;
        }

        .action-pay:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
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
            margin: 0 0 20px 0;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

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

        .modal-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .modal-header h3 {
            margin: 0;
            color: #1f2937;
            font-size: 18px;
        }

        .modal-body {
            margin-bottom: 25px;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-group label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-group span {
            display: block;
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn-confirm {
            background: #047857;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #065f46;
        }

        .modal-btn-cancel {
            background: #f3f4f6;
            color: #1f2937;
        }

        .modal-btn-cancel:hover {
            background: #e5e7eb;
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
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

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
            }

            .search-box {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            th,
            td {
                padding: 12px 8px;
                font-size: 12px;
            }

            .action-btn {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-file-invoice-dollar"></i> Mis Deudas</h1>
            <p>Gestiona todas las cuentas por cobrar</p>
        </div>

        <a href="/institution/dashboard" class="btn btn-nav">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Content Card -->
        <div class="content-card">
            <!-- Header con búsqueda -->
            <div class="card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    Lista de Deudas
                </h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar por cliente, descripción...">
                </div>
                <div class="header-actions">
                    <a href="/institution/deudas/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Nueva Deuda</span>
                    </a>
                </div>
            </div>

            <!-- Contenido -->
            <div id="tableContainer">
                <div class="loading">
                    <div class="spinner-border"></div>
                    <p style="color: #6b7280; margin-top: 15px;">Cargando deudas...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para marcar como pagada -->
    <div class="modal" id="payModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Marcar como Pagada</h3>
            </div>
            <div class="modal-body">
                <div class="info-group">
                    <label>Cliente</label>
                    <span id="modalClient"></span>
                </div>
                <div class="info-group">
                    <label>Monto</label>
                    <span id="modalAmount"></span>
                </div>
                <div class="info-group">
                    <label>Descripción</label>
                    <span id="modalDescription"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">
                    Cancelar
                </button>
                <button class="modal-btn modal-btn-confirm" id="confirmPayBtn">
                    Confirmar Pago
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del cliente -->
    <div class="modal" id="detailModal">
        <!-- El contenido se carga dinámicamente -->
    </div>

@endsection

@section('scripts')
    <script>
        // Verificar autenticación
        if (!localStorage.getItem('token')) {
            window.location.href = '/login';
        }

        let deudasCompletas = [];
        let deudaSeleccionada = null;

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            cargarDeudas();

            // Búsqueda en tiempo real
            document.getElementById('searchInput').addEventListener('input', function () {
                mostrarTabla();
            });

            // Cerrar modal al hacer click fuera
            document.getElementById('payModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    cerrarModal();
                }
            });

            document.getElementById('detailModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    cerrarModalDetalle();
                }
            });
        });

        async function cargarDeudas() {
            try {
                const response = await API_CONFIG.call('institution/deudas', 'GET');

                if (response.success) {
                    deudasCompletas = response.data || [];
                    mostrarTabla();
                } else {
                    mostrarMensaje(response.message || 'Error al cargar deudas', 'error');
                    mostrarContenidoVacio();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
                mostrarContenidoVacio();
            }
        }

        function mostrarTabla() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            // Filtrar deudas
            const deudasFiltradas = deudasCompletas.filter(deuda => {
                const cliente = deuda.client?.nombre?.toLowerCase() || '';
                const descripcion = deuda.descripcion?.toLowerCase() || '';

                return cliente.includes(searchTerm) || descripcion.includes(searchTerm);
            });

            if (deudasFiltradas.length === 0) {
                mostrarContenidoVacio();
                return;
            }

            // Generar tabla
            let html = `
                                        <div class="table-container">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Cliente</th>
                                                        <th>Descripción</th>
                                                        <th>Monto</th>
                                                        <th>Fecha Vencimiento</th>
                                                        <th>Estado</th>
                                                        <th style="text-align: center;">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    `;

            deudasFiltradas.forEach(deuda => {
                const estado = getEstadoBadge(deuda.estado, deuda.fecha_vencimiento);
                const puedeMarcarPago = deuda.estado !== 'pagada';
                const monto = parseFloat(deuda.monto).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                const fechaVencimiento = formatDate(deuda.fecha_vencimiento);
                const clienteNombre = deuda.client?.nombre || 'Sin cliente';

                html += `
                                            <tr>
                                                <td>
                                                    <div class="debt-client" style="cursor: pointer;" onclick="abrirModalDetalle(${deuda.id_cuenta})">
                                                        ${clienteNombre}
                                                    </div>
                                                    <div style="font-size: 12px; color: #6b7280;">${deuda.client?.correo || ''}</div>
                                                </td>
                                                <td>${deuda.descripcion}</td>
                                                <td>
                                                    <span class="debt-amount">$${monto}</span>
                                                </td>
                                                <td>${fechaVencimiento}</td>
                                                <td>${estado}</td>
                                                <td style="text-align: center;">
                                                    <div class="actions">
                                                        <button class="action-btn action-pay" 
                                                            onclick="abrirModal(${deuda.id_cuenta})"
                                                            ${!puedeMarcarPago ? 'disabled' : ''}>
                                                            <i class="fas fa-check"></i>
                                                            Pagar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;
            });

            html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    `;

            document.getElementById('tableContainer').innerHTML = html;
        }

        function getEstadoBadge(estado, fechaVencimiento) {
            let badge = '';
            const hoy = new Date();
            const fecha = new Date(fechaVencimiento);

            if (estado === 'Pagada') {
                badge = '<span class="badge badge-pagada">Pagada</span>';
            } else if (fecha < hoy) {
                badge = '<span class="badge badge-vencido">Vencido</span>';
            } else {
                badge = '<span class="badge badge-pendiente">Pendiente</span>';
            }

            return badge;
        }

        function abrirModalDetalle(deudaId) {
            const deuda = deudasCompletas.find(d => d.id_cuenta === deudaId);
            if (!deuda || !deuda.client) return;

            const cliente = deuda.client;
            const monto = parseFloat(deuda.monto).toLocaleString('es-CO', { minimumFractionDigits: 2 });
            const estado = deuda.estado === 'pagada' ? 'Pagada' : 'Pendiente';

            const html = `
                                        <div class="modal-header">
                                            <h3><i class="fas fa-user-info"></i> Información del Cliente</h3>
                                        </div>
                                        <div class="modal-body">
                                            <div class="info-group">
                                                <label>Nombre</label>
                                                <span>${cliente.nombre}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Correo</label>
                                                <span>${cliente.correo}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Teléfono</label>
                                                <span>${cliente.telefono}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Descripción de la Deuda</label>
                                                <span>${deuda.descripcion}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Monto de la Deuda</label>
                                                <span style="font-size: 18px; font-weight: 700; color: #047857;">$${monto}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Fecha de Emisión</label>
                                                <span>${formatDate(deuda.fecha_emision)}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Fecha de Vencimiento</label>
                                                <span>${formatDate(deuda.fecha_vencimiento)}</span>
                                            </div>
                                            <div class="info-group">
                                                <label>Estado de Pago</label>
                                                <span>${estado}</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalDetalle()">
                                                Cerrar
                                            </button>
                                        </div>
                                    `;

            document.getElementById('detailModal').innerHTML = `<div class="modal-content">${html}</div>`;
            document.getElementById('detailModal').classList.add('show');
        }

        function cerrarModalDetalle() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function abrirModal(deudaId) {
            const deuda = deudasCompletas.find(d => d.id_cuenta === deudaId);
            if (!deuda) return;

            deudaSeleccionada = deuda;

            // Llenar datos del modal
            document.getElementById('modalClient').textContent = deuda.client?.nombre || 'N/A';
            document.getElementById('modalAmount').textContent = `$${parseFloat(deuda.monto).toLocaleString('es-CO', { minimumFractionDigits: 2 })}`;
            document.getElementById('modalDescription').textContent = deuda.descripcion;

            // Mostrar modal
            document.getElementById('payModal').classList.add('show');

            // Asignar evento al botón de confirmación
            document.getElementById('confirmPayBtn').onclick = function () {
                marcarComoPagada(deudaId);
            };
        }

        function cerrarModal() {
            document.getElementById('payModal').classList.remove('show');
            deudaSeleccionada = null;
        }

        async function marcarComoPagada(deudaId) {
            cerrarModal();

            const btn = document.getElementById('confirmPayBtn');
            btn.disabled = true;
            btn.textContent = 'Procesando...';

            try {
                // Llamar sin enviar datos
                const response = await API_CONFIG.call(`institution/deudas/${deudaId}/pagar`, 'PUT', {});

                btn.disabled = false;
                btn.textContent = 'Confirmar Pago';

                if (response.success) {
                    mostrarMensaje('Deuda marcada como pagada exitosamente', 'success');
                    cargarDeudas();
                } else {
                    mostrarMensaje(response.message || 'Error al marcar como pagado', 'error');
                }
            } catch (error) {
                btn.disabled = false;
                btn.textContent = 'Confirmar Pago';
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
            }
        }


        function mostrarContenidoVacio() {
            document.getElementById('tableContainer').innerHTML = `
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h3>Sin deudas</h3>
                                            <p>No hay deudas que coincidan con tu búsqueda</p>
                                            <a href="/institution/deudas/crear" class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                                Crear primera deuda
                                            </a>
                                        </div>
                                    `;
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
    </script>
@endsection