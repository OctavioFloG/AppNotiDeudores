<!DOCTYPE html>
<html>

<head>
    <title>Panel Institucional</title>
</head>

<body>
    <h2>Bienvenido {{ $user->usuario }}</h2>
    <h3>Datos de la Institución:</h3>
    <ul>
        <li>Nombre: {{ $institution->nombre }}</li>
        <li>Correo: {{ $institution->correo }}</li>
        <li>Teléfono: {{ $institution->telefono }}</li>
        <li>Dirección: {{ $institution->direccion }}</li>
    </ul>
    <a href="{{ route('institucional.cambiar-contrasena') }}">Cambiar mi contraseña</a>
    <br>
    <a href="{{ route('institucional.logout') }}">Cerrar sesión</a>
</body>

</html>