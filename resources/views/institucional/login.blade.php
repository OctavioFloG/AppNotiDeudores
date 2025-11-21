<!DOCTYPE html>
<html>

<head>
    <title>Login Institución</title>
</head>

<body>
    <h2>Acceso Institucional</h2>
    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif
    <form action="{{ route('institucional.login') }}" method="POST">
        @csrf
        <label>Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label>Contraseña:</label>
        <input type="password" name="contrasena" required>
        <br>
        <button type="submit">Ingresar</button>
    </form>
</body>

</html>