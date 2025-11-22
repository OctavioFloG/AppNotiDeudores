<!DOCTYPE html>
<html>
<head>
    <title>Cambiar Contraseña</title>
</head>
<body>
    <h2>Cambiar mi contraseña</h2>
    
    <!-- Contenedor para mensajes -->
    <div id="messageContainer"></div>

    <form id="changePasswordForm">
        <label>Contraseña actual:</label>
        <input type="password" id="contrasena_actual" name="contrasena_actual" required>
        <br>

        <label>Nueva contraseña:</label>
        <input type="password" id="contrasena_nueva" name="contrasena_nueva" required>
        <br>

        <label>Confirmar nueva contraseña:</label>
        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
        <br>

        <button type="submit">Cambiar contraseña</button>
    </form>
    
    <a href="{{ route('institution.dashboard') }}">Volver al panel</a>

    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const token = localStorage.getItem('token'); // Token guardado en login
            const contrasena_actual = document.getElementById('contrasena_actual').value;
            const contrasena_nueva = document.getElementById('contrasena_nueva').value;
            const confirmar_contrasena = document.getElementById('confirmar_contrasena').value;

            fetch('http://appnotideudores.test/api/auth/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify({
                    contrasena_actual: contrasena_actual,
                    contrasena_nueva: contrasena_nueva,
                    confirmar_contrasena: confirmar_contrasena
                })
            })
            .then(res => res.json())
            .then(data => {
                const messageDiv = document.getElementById('messageContainer');
                if (data.success) {
                    messageDiv.innerHTML = `<div style="color: green; background-color: #f0fff0; padding: 10px; border-radius: 5px;">✓ ${data.message}</div>`;
                    document.getElementById('changePasswordForm').reset();
                } else {
                    messageDiv.innerHTML = `<div style="color: red; background-color: #fff5f5; padding: 10px; border-radius: 5px;">✗ ${data.message}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('messageContainer').innerHTML = `<div style="color: red;">Error: ${err.message}</div>`;
            });
        });
    </script>
</body>
</html>
