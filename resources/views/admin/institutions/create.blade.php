<!DOCTYPE html>
<html>
<head>
    <title>Registrar Nueva Institución</title>
</head>
<body>
    <h2>Registrar Nueva Institución</h2>
    
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.institutions.store') }}" method="POST">
        @csrf
        
        <label>Nombre de la institución:</label>
        <input type="text" name="nombre" required value="{{ old('nombre') }}">
        <br>

        <label>Dirección:</label>
        <input type="text" name="direccion" required value="{{ old('direccion') }}">
        <br>

        <label>Correo:</label>
        <input type="email" name="correo" required value="{{ old('correo') }}">
        <br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" required value="{{ old('telefono') }}">
        <br>

        <button type="submit">Registrar institución</button>
    </form>
    
    <a href="{{ route('admin.dashboard') }}">Volver al panel</a>
</body>
</html>
