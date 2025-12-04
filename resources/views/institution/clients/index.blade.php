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
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 30px 20px;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

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

    .btn-secondary {
        background: #f3f4f6;
        color: #1f2937;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

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

    .debt-badge {
        display: inline-block;
        background: linear-gradient(135deg, #fef3c7 0%, #fee2e2 100%);
        color: #92400e;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        min-width: 40px;
    }

    .debt-badge.has-debts {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

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

    .action-send-token {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .action-send-token:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .action-send-token:disabled {
        background: #d1d5db;
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
        margin: 0 0 20px 0;
    }

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

    /* ===== Modal ayuda Excel ===== */

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
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
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
    }

    .modal-body {
        margin-bottom: 20px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .modal-btn-cancel {
        background: #f3f4f6;
        color: #1f2937;
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
<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fas fa-users"></i> Mis Clientes</h1>
        <p>Gestiona todos los clientes registrados</p>
    </div>

    <a href="/institution/dashboard" class="btn btn-nav">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
</div>

<div class="container-main">
    <div id="messageContainer"></div>

    <div class="content-card">
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
                <button class="btn btn-secondary" onclick="document.getElementById('fileClientes').click()">
                    <i class="fas fa-file-import"></i>
                    <span>Importar Excel</span>
                </button>

                <button class="btn btn-nav" onclick="abrirAyudaImport()">
                    <i class="fas fa-question-circle"></i>
                    <span>Ayuda Excel</span>
                </button>

                <input type="file" id="fileClientes" accept=".xlsx,.xls,.csv" style="display:none" onchange="importarClientes()">

                <a href="/institution/clientes/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo Cliente</span>
                </a>
            </div>
        </div>

        <div id="tableContainer">
            <div class="loading">
                <div class="spinner-border"></div>
                <p style="color: #6b7280; margin-top: 15px;">Cargando clientes...</p>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalAyudaImport">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-file-excel"></i> Formato del archivo Excel</h3>
        </div>
        <div class="modal-body">
            <p>Tu archivo debe cumplir con lo siguiente:</p>
            <ul style="margin-left:18px; margin-top:10px;">
                <li>Debe ser .xlsx, .xls o .csv.</li>
                <li>La primera fila debe contener los encabezados.</li>
                <li>Encabezados esperados (en minúsculas): <strong>nombre</strong>, <strong>telefono</strong>, <strong>correo</strong>, <strong>direccion</strong> (opcional).</li>
                <li>Cada fila representa un cliente de la misma institución.</li>
                <li>El correo es opcional, pero si viene debe tener un formato válido.</li>
                <li><strong>EVITA</strong> duplicados en teléfono o correo dentro del mismo archivo.</li>
                <li>Evita filas o columnas vacías dentro de los datos.</li>
            </ul>
            <p style="margin-top:10px;">Ejemplo de encabezados:</p>
            <pre style="background:#f3f4f6;padding:10px;border-radius:6px;">
nombre | telefono | correo | direccion
Juan Pérez | 2721493602 | juan@example.com | Calle 1 #123
            </pre>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" onclick="cerrarAyudaImport()">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    if (!localStorage.getItem('token')) {
        window.location.href = '/login';
    }

    let clientesCompletos = [];
    let paginaActual = 1;
    const itemsPorPagina = 10;

    document.addEventListener('DOMContentLoaded', function () {
        cargarClientes();

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

                for (let cliente of clientesCompletos) {
                    await cargarDemasCliente(cliente);
                }

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

    async function cargarDemasCliente(cliente) {
        try {
            const response = await API_CONFIG.call(`institution/clientes/${cliente.id_cliente}`, 'GET');

            if (response.success && response.data) {
                cliente.numero_deudas = response.data.deudas ? response.data.deudas.length : 0;
                cliente.monto_total_deudas = response.data.deudas ?
                    response.data.deudas.reduce((sum, d) => sum + (d.monto || 0), 0) : 0;
            } else {
                cliente.numero_deudas = 0;
                cliente.monto_total_deudas = 0;
            }
        } catch (error) {
            cliente.numero_deudas = 0;
            cliente.monto_total_deudas = 0;
        }
    }

    function mostrarTabla(pagina) {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();

        const clientesFiltrados = clientesCompletos.filter(cliente => {
            const nombre = (cliente.nombre || '').toLowerCase();
            const telefono = (cliente.telefono || '').toLowerCase();
            const correo = (cliente.correo || '').toLowerCase();

            return nombre.includes(searchTerm) ||
                telefono.includes(searchTerm) ||
                correo.includes(searchTerm);
        });

        if (clientesFiltrados.length === 0) {
            mostrarContenidoVacio();
            return;
        }

        const totalPaginas = Math.ceil(clientesFiltrados.length / itemsPorPagina);
        const inicio = (pagina - 1) * itemsPorPagina;
        const fin = inicio + itemsPorPagina;
        const clientesPagina = clientesFiltrados.slice(inicio, fin);

        let html = `
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th style="text-align: center;">Deudas</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;

        clientesPagina.forEach(cliente => {
            const numeroDeudas = cliente.numero_deudas || 0;
            const badgeClass = numeroDeudas > 0 ? 'has-debts' : '';

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
                        <span class="debt-badge ${badgeClass}">${numeroDeudas}</span>
                    </td>
                    <td style="text-align: center;">
                        <div class="actions" style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            <button class="action-btn action-send-token" onclick="enviarTokenWhatsApp(${cliente.id_cliente}, '${cliente.nombre}', '${cliente.telefono}')" title="Enviar notificación por WhatsApp">
                                <i class="fas fa-paper-plane"></i>
                                Notificar
                            </button>

                            <button class="action-btn action-view" onclick="verHistorial(${cliente.id_cliente})" title="Ver historial de deudas">
                                <i class="fas fa-history"></i>
                                Historial
                            </button>

                            <button class="action-btn action-delete" onclick="eliminarCliente(${cliente.id_cliente}, '${cliente.nombre}')" title="Eliminar cliente">
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
                        Pagina ${pagina} de ${totalPaginas}
                    </span>
                    <button onclick="mostrarTabla(${pagina + 1})" ${pagina === totalPaginas ? 'disabled class="disabled"' : ''}>
                        Siguiente
                    </button>
                    <button onclick="mostrarTabla(${totalPaginas})" ${pagina === totalPaginas ? 'disabled class="disabled"' : ''}>
                        Ultima <i class="fas fa-chevron-right"></i>
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
                <p>No hay clientes que coincidan con tu busqueda</p>
                <a href="/institution/clientes/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Crear primer cliente
                </a>
            </div>
        `;
    }

    function eliminarCliente(id, nombre) {
        if (confirm('¿Estás seguro que deseas eliminar a ' + nombre + '?')) {
            API_CONFIG.call(`institution/clientes/${id}`, 'DELETE')
                .then(response => {
                    if (response.success) {
                        mostrarMensaje('Cliente eliminado correctamente', 'success');
                        clientesCompletos = clientesCompletos.filter(c => c.id_cliente !== id);
                        mostrarTabla(paginaActual);
                    } else {
                        mostrarMensaje(response.message || 'Error al eliminar cliente', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje('Error de conexión', 'error');
                });
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

    async function enviarTokenWhatsApp(clienteId, clienteNombre, clienteTelefono) {
        if (!confirm(`¿Enviar notificación de deudas a ${clienteNombre} (${clienteTelefono})?`)) {
            return;
        }

        const btn = event.target.closest('.action-send-token');
        const btnText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

        try {
            const response = await API_CONFIG.call(
                `enviar-deudas-link/${clienteId}`,
                'POST'
            );

            if (response.success) {
                mostrarModalNotificacion(response.data);
                mostrarAlerta(`Notificación enviada exitosamente a ${clienteNombre}`, 'success');
            } else {
                mostrarAlerta(response.message || 'Error al enviar notificación', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = btnText;
        }
    }

    function mostrarModalNotificacion(data) {
        const modal = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 9999;" id="modal-notificacion">
                <div style="background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
                        <h2 style="color: #1f2937; margin: 0;">Notificación Enviada</h2>
                    </div>

                    <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Información del Envío</div>
                        <p style="margin: 8px 0; color: #1f2937;"><strong>Cliente:</strong> ${data.cliente_nombre}</p>
                        <p style="margin: 8px 0; color: #1f2937;"><strong>Telefono:</strong> ${data.cliente_telefono}</p>
                        <p style="margin: 8px 0; color: #1f2937;"><strong>Deudas:</strong> ${data.total_deudas}</p>
                        <p style="margin: 8px 0; color: #1f2937;"><strong>Monto Total:</strong> $${data.monto_total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}</p>
                    </div>

                    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dbeafe 100%); border: 2px solid #047857; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 10px;">Link de Acceso</div>
                        <div style="background: white; padding: 12px; border-radius: 8px; word-break: break-all; font-family: 'Courier New', monospace; font-size: 11px; color: #047857; margin-bottom: 10px; max-height: 60px; overflow-y: auto;">
                            ${data.link}
                        </div>
                        <button onclick="copiarLinkModal('${data.link}')" style="width: 100%; padding: 10px; background: #047857; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                            <i class="fas fa-copy"></i> Copiar Link
                        </button>
                    </div>

                    <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                        <p style="margin: 0; color: #92400e; font-size: 13px;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> El cliente recibió un mensaje por WhatsApp con el link directo para ver sus deudas. El link expira en 5 minutos.
                        </p>
                    </div>

                    <button onclick="cerrarModalNotificacion()" style="width: 100%; padding: 12px; background: #047857; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                        Cerrar
                    </button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modal);
    }

    function copiarLinkModal(link) {
        navigator.clipboard.writeText(link).then(() => {
            mostrarAlerta('Link copiado al portapapeles', 'success');
        });
    }

    function cerrarModalNotificacion() {
        const modal = document.getElementById('modal-notificacion');
        if (modal) {
            modal.remove();
        }
    }

    function mostrarAlerta(mensaje, tipo) {
        const alerta = document.createElement('div');
        alerta.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            ${tipo === 'success' 
                ? 'background: #d1fae5; color: #065f46; border-left: 4px solid #10b981;' 
                : 'background: #fee2e2; color: #991b1b; border-left: 4px solid #dc2626;'}
        `;
        alerta.textContent = mensaje;
        document.body.appendChild(alerta);

        setTimeout(() => {
            alerta.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => alerta.remove(), 300);
        }, 4000);
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    async function importarClientes() {
        const input = document.getElementById('fileClientes');
        if (!input.files.length) {
            mostrarMensaje('Selecciona un archivo de Excel', 'error');
            return;
        }

        const btnImport = document.querySelector('.header-actions .btn-secondary');
        const btnTextOriginal = btnImport.innerHTML;

        const formData = new FormData();
        formData.append('file', input.files[0]);

        // Loading ON
        btnImport.disabled = true;
        btnImport.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';

        try {
            const resp = await fetch('/api/institution/clientes/import', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                },
                body: formData
            });

            const data = await resp.json();

            if (data.success) {
                mostrarMensaje(data.message || 'Clientes importados correctamente', 'success');
                cargarClientes && cargarClientes();
            } else {
                let msg = data.message || 'Error al importar clientes';

                // Si viene detalle tipo "The 2.telefono field must be a string."
                if (data.error) {
                    msg += ` Detalle: ${data.error}`;
                }

                mostrarMensaje(msg, 'error');
                console.error('Import error:', data);
            }
        } catch (e) {
            console.error(e);
            mostrarMensaje('Error de conexión al importar clientes', 'error');
        } finally {
            // Reset input y botón
            input.value = '';
            btnImport.disabled = false;
            btnImport.innerHTML = btnTextOriginal;
        }
    }


    function abrirAyudaImport() {
        document.getElementById('modalAyudaImport').classList.add('show');
    }

    function cerrarAyudaImport() {
        document.getElementById('modalAyudaImport').classList.remove('show');
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('modalAyudaImport');
        if (!modal) return;
        if (e.target === modal) {
            cerrarAyudaImport();
        }
    });
</script>
@endsection
