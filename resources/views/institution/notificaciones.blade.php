@extends('layouts.app')

@section('title', 'Notificaciones enviadas - AppNotiDeudores')

@section('styles')
<style>
    body { background: #f9fafb; }
    .content-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
        margin: 40px auto 0 auto;
        max-width: 1200px;
    }
    .section-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6;
    }
    .section-header h2 { margin: 0; font-size: 20px; color: #1f2937; }
    .noti-table { width: 100%; border-collapse: collapse; }
    .noti-table th, .noti-table td { padding: 12px; font-size: 14px; }
    .noti-table th { background: #f9fafb; color: #6b7280; font-weight: 600; }
    .noti-table tr { border-bottom: 1px solid #e5e7eb; }
    .badge-enviado {
        background: #d1fae5; color: #047857; padding: 5px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;
    }
    .badge-fallido {
        background: #fee2e2; color: #ef4444; padding: 5px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="content-section">
    <div class="section-header">
        <h2><i class="fas fa-paper-plane"></i> Notificaciones enviadas</h2>
        <a href="/institution/dashboard" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Regresar</a>
    </div>
    <div id="notificacionesContainer">
        <div style="text-align: center;color:#9ca3af;padding:40px;">
            <i class="fas fa-spinner fa-spin" style="font-size:40px;"></i>
            <p>Cargando notificaciones...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    cargarNotificaciones();
});

async function cargarNotificaciones() {
    try {
        const response = await API_CONFIG.call('institution/notificaciones', 'GET');
        renderTabla(response.data || []);
    } catch (error) {
        renderError();
    }
}

function renderTabla(datos) {
    const container = document.getElementById('notificacionesContainer');
    if (!datos.length) {
        container.innerHTML = '<div style="text-align:center;color:#9ca3af;padding:40px;"><i class="fas fa-inbox" style="font-size:40px;"></i><p>No hay notificaciones registradas.</p></div>';
        return;
    }
    let html = `
        <div style="overflow-x:auto;">
        <table class="noti-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
    `;
    datos.forEach(noti => {
        html += `
            <tr>
                <td>${formatearFecha(noti.fecha)}</td>
                <td>${noti.cliente}</td>
                <td>${noti.tipo || '-'}</td>
                <td>${noti.mensaje ? noti.mensaje.substr(0,60)+'...' : '-'}</td>
                <td>${renderBadge(noti.estado)}</td>
            </tr>
        `;
    });
    html += `</tbody></table></div>`;
    container.innerHTML = html;
}

function renderBadge(estado) {
    if ((estado+'').toLowerCase() === 'enviada') return '<span class="badge-enviado">Enviado</span>';
    else return '<span class="badge-fallido">Fallido</span>';
}

function formatearFecha(fecha) {
    if (!fecha) return '-';
    const d = new Date(fecha);
    return d.toLocaleString('es-MX');
}

function renderError() {
    const container = document.getElementById('notificacionesContainer');
    container.innerHTML = '<p style="color:#ef4444;text-align:center;">Error al cargar notificaciones</p>';
}
</script>
@endsection
