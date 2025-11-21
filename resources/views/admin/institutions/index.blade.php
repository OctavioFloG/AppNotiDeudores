<!DOCTYPE html>
<html>
<head>
    <title>Instituciones</title>
</head>
<body>
    <h2>Gestión de Instituciones</h2>
    
    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.institutions.create') }}">Registrar nueva institución</a>
    <br><br>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($institutions as $inst)
                <tr>
                    <td>{{ $inst->nombre }}</td>
                    <td>{{ $inst->correo }}</td>
                    <td>{{ $inst->telefono }}</td>
                    <td>{{ $inst->direccion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.dashboard') }}">Volver al panel</a>
</body>
</html>
