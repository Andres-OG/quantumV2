<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edición de Maestro - Gestión de Instituciones Educativas">
    <title>Editar Maestro</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar Maestro</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <form action="{{ route('maestros.update', $maestro->id) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="nombre">Nombre del Maestro:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $maestro->nombre }}" required>

            <label for="no_cuenta">Número de Cuenta:</label>
            <input type="text" name="no_cuenta" id="no_cuenta" class="form-control" value="{{ $maestro->no_cuenta }}" required>

            <label for="carrera_id">Carrera:</label>
            <select name="carrera_id" id="carrera_id" class="form-control" required>
                @foreach ($carreras as $carrera)
                    <option value="{{ $carrera->id }}" {{ $maestro->carrera_id == $carrera->id ? 'selected' : '' }}>{{ $carrera->nombre }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('maestros.index') }}" class="btn btn-secondary">Volver al Listado de Maestros</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
