@extends('layouts.app')

@section('title', 'Instituciones - AppNotiDeudores')

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
            background: white;
            color: #047857;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 30px 20px;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
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

        .action-buttons {
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
            gap: 5px;
        }

        .action-btn-edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .action-btn-edit:hover {
            background: #c7d2fe;
        }

        .action-btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-btn-delete:hover {
            background: #fecaca;
        }

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
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            th,
            td {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-list"></i> Instituciones</h1>
            <p>Gestiona todas las instituciones del sistema</p>
        </div>
        <div>
            <a href="/admin/institutions/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Institución
            </a>
            <a href="/admin/dashboard" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>

    </div>

    <!-- Main Container -->
    <div class="container-main">
        <!-- Alertas -->
        <div id="messageContainer"></div>

        <!-- Contenido -->
        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-building"></i> Lista de Instituciones</h2>
            </div>

            <div id="tableContainer">
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <p style="color: #6b7280;">Cargando instituciones...</p>
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

        // Cargar instituciones al abrir la página
        document.addEventListener('DOMContentLoaded', function () {
            cargarInstituciones();
        });

        async function cargarInstituciones() {
            try {
                const response = await API_CONFIG.call('institutions', 'GET');

                if (response.success) {
                    mostrarInstituciones(response.data);
                } else {
                    mostrarMensaje('Error al cargar instituciones', 'error');
                    mostrarVacio();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'error');
                mostrarVacio();
            }
        }

        function mostrarInstituciones(instituciones) {
            const container = document.getElementById('tableContainer');

            if (!instituciones || instituciones.length === 0) {
                mostrarVacio();
                return;
            }

            let html = `
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

            instituciones.forEach(inst => {
                html += `
                        <tr>
                            <td><strong>${inst.nombre || 'N/A'}</strong></td>
                            <td>${inst.correo || 'N/A'}</td>
                            <td>${inst.telefono || 'N/A'}</td>
                            <td>${inst.direccion || 'N/A'}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn action-btn-edit" onclick="editarInstituccion(${inst.id_institucion})">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="action-btn action-btn-delete" onclick="eliminarInstituccion(${inst.id_institucion})">
                                        <i class="fas fa-trash"></i> Eliminar
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

            container.innerHTML = html;
        }

        function mostrarVacio() {
            const container = document.getElementById('tableContainer');
            container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>Sin instituciones</h3>
                        <p>No hay instituciones registradas. <a href="/admin/institutions/create" style="color: #047857; font-weight: 600;">Crear primera institución</a></p>
                    </div>
                `;
        }

        function editarInstituccion(id) {
            alert('Función editar en desarrollo');
        }

        function eliminarInstituccion(id) {
            if (confirm('¿Estás seguro de eliminar esta institución?')) {
                alert('Función eliminar en desarrollo');
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
    </script>
@endsection