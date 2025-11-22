@extends('layouts.app')

@section('title', 'Clientes - AppNotiDeudores')

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
            max-width: 1200px;
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

        .client-name {
            font-weight: 600;
            color: #047857;
        }

        .client-info {
            font-size: 13px;
            color: #6b7280;
        }

        /* Acciones */
        .actions {
            display: flex;
            gap: 8px;
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

        .action-view {
            background: #dbeafe;
            color: #0369a1;
        }

        .action-view:hover {
            background: #bfdbfe;
        }

        .action-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-delete:hover {
            background: #fecaca;
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

        /* Paginación */
        .pagination-container {
            padding: 25px;
            border-top: 2px solid #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .pagination {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .pagination button,
        .pagination a {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #1f2937;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
        }

        .pagination button:hover,
        .pagination a:hover {
            border-color: #047857;
            color: #047857;
            background: #f3f4f6;
        }

        .pagination .active {
            background: #047857;
            color: white;
            border-color: #047857;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        <h1><i class="fas fa-users"></i> Mis Clientes</h1>
        <p>Gestiona todos los clientes registrados</p>
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
                    Lista de Clientes
                </h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar por nombre, teléfono o correo...">
                </div>
                <div class="header-actions">
                    <a href="/institution/clientes/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Nuevo Cliente</span>
                    </a>
                </div>
            </div>

            <!-- Contenido -->
            <div id="tableContainer">
                <div class="loading">
                    <div class="spinner-border"></div>
                    <p style="color: #6b7280; margin-top: 15px;">Cargando clientes...</p>
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

        let clientesCompletos = [];
        let paginaActual = 1;
        const itemsPorPagina = 10;

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            cargarClientes();

            // Búsqueda en tiempo real
            document.getElementById('searchInput').addEventListener('input', function () {
                paginaActual = 1;
                mostrarTabla(1);
            });
        });

        async function cargarClientes() {
            try {
                const response = await API_CONFIG.call('institution/clientes', 'GET');

                if (response.success) {
                    clientesCompletos = response.data || [];
                    mostrarTabla(1);
                } else {
                    mostrarMensaje(response.message || 'Error al cargar clientes', 'error');
                    mostrarContenidoVacio();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
                mostrarContenidoVacio();
            }
        }

        function mostrarTabla(pagina) {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            // Filtrar clientes
            const clientesFiltrados = clientesCompletos.filter(cliente => {
                const nombre = cliente.nombre.toLowerCase();
                const telefono = cliente.telefono.toLowerCase();
                const correo = cliente.correo.toLowerCase();

                return nombre.includes(searchTerm) ||
                    telefono.includes(searchTerm) ||
                    correo.includes(searchTerm);
            });

            if (clientesFiltrados.length === 0) {
                mostrarContenidoVacio();
                return;
            }

            // Calcular paginación
            const totalPaginas = Math.ceil(clientesFiltrados.length / itemsPorPagina);
            const inicio = (pagina - 1) * itemsPorPagina;
            const fin = inicio + itemsPorPagina;
            const clientesPagina = clientesFiltrados.slice(inicio, fin);

            // Generar tabla
            let html = `
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th style="text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

            clientesPagina.forEach(cliente => {
                html += `
                        <tr>
                            <td>
                                <div class="client-name">${cliente.nombre}</div>
                            </td>
                            <td>${cliente.telefono}</td>
                            <td>
                                <div class="client-info">${cliente.correo}</div>
                            </td>
                            <td style="text-align: center;">
                                <div class="actions" style="justify-content: center;">
                                    <button class="action-btn action-view" onclick="verHistorial(${cliente.id_cliente})">
                                        <i class="fas fa-history"></i>
                                        Historial
                                    </button>
                                    <button class="action-btn action-delete" onclick="eliminarCliente(${cliente.id_cliente}, '${cliente.nombre}')">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
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

            // Agregar paginación
            if (totalPaginas > 1) {
                html += `
                    <div class="pagination-container">
                        <div class="pagination">
                            <button onclick="mostrarTabla(1)" ${pagina === 1 ? 'disabled class="disabled"' : ''}>
                                <i class="fas fa-chevron-left"></i> Primera
                            </button>
                            <button onclick="mostrarTabla(${pagina - 1})" ${pagina === 1 ? 'disabled class="disabled"' : ''}>
                                Anterior
                            </button>
                            <span style="padding: 0 12px; color: #6b7280; font-size: 13px;">
                                Página ${pagina} de ${totalPaginas}
                            </span>
                            <button onclick="mostrarTabla(${pagina + 1})" ${pagina === totalPaginas ? 'disabled class="disabled"' : ''}>
                                Siguiente
                            </button>
                            <button onclick="mostrarTabla(${totalPaginas})" ${pagina === totalPaginas ? 'disabled class="disabled"' : ''}>
                                Última <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    `;
            }

            document.getElementById('tableContainer').innerHTML = html;
        }

        function mostrarContenidoVacio() {
            document.getElementById('tableContainer').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>Sin clientes</h3>
                    <p>No hay clientes que coincidan con tu búsqueda</p>
                    <a href="/institution/clientes/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear primer cliente
                    </a>
                </div>
                `;
        }

        function verCliente(id) {
            // Por ahora, podrías ir a una página de detalles
            console.log('Ver cliente:', id);
            // window.location.href = '/institution/clientes/' + id;
        }

        function eliminarCliente(id, nombre) {
            if (confirm(`¿Estás seguro que deseas eliminar a ${nombre}?`)) {
                // Implementar eliminación
                console.log('Eliminar cliente:', id);
            }
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

        function verHistorial(clienteId) {
            window.location.href = `/institution/clientes/${clienteId}`;
        }

    </script>
@endsection