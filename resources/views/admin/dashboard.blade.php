<!DOCTYPE html>
<html>

<head>
    <title>Panel Administrador</title>
</head>

<body>
    <h2>Panel de administración</h2>
    <p>Bienvenido, {{ $user->usuario }} ({{ $user->rol }})</p>

    <h3>Estadísticas globales:</h3>
    <ul>
        <li>Total de instituciones: {{ $stats['instituciones'] }}</li>
        <li>Total de usuarios: {{ $stats['usuarios'] }}</li>
        <li>Total de deudas: {{ $stats['deudas'] }}</li>
        <li>Deudas pendientes: {{ $stats['deudas_pendientes'] }}</li>
        <li>Deudas vencidas: {{ $stats['deudas_vencidas'] }}</li>
        <li>Deudas pagadas: {{ $stats['deudas_pagadas'] }}</li>
    </ul>

    <h3>Opciones de administración:</h3>
    <ul>
        <li><a href="{{ route('admin.institutions.index') }}">Gestionar instituciones</a></li>
    </ul>

    <a href="{{ route('institucional.logout') }}">Cerrar sesión</a>
</body>

</html>