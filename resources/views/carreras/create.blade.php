<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Carrera - Gestión de Instituciones Educativas">
    <title>Registrar Carrera</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registrar Nueva Carrera</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <div class="title">
            <h3>Formulario de Registro de Carrera</h3>
        </div>

        <form action="{{ route('carreras.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre de la Carrera:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Registrar Carrera</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('carreras.index') }}" class="btn btn-secondary">Volver al Listado de Carreras</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
