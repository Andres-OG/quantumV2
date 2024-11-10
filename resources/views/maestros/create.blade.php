<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Maestro - Gestión de Instituciones Educativas">
    <title>Registrar Maestro</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registrar Nuevo Maestro</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <form action="{{ route('maestros.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre del Maestro:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="no_cuenta">Número de Cuenta:</label>
            <input type="text" name="no_cuenta" id="no_cuenta" class="form-control" required value="{{ old('no_cuenta') }}">
            @error('no_cuenta')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="carrera_id">Carrera:</label>
            <select name="carrera_id" id="carrera_id" class="form-control" required>
                <option value="">Seleccione una carrera</option>
                @foreach ($carreras as $carrera)
                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                        {{ $carrera->nombre }}
                    </option>
                @endforeach
            </select>
            @error('carrera_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Registrar Maestro</button>
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
