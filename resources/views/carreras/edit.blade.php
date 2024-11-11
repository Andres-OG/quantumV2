<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Editar Carrera - Gestión de Instituciones Educativas">
    <title>Editar Carrera</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Personaliza tu plantel</h1>
            <div>
                <h1>Bienvenido: {{ $name ?? 'Usuario' }}</h1>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <div class="title">
            <h1>Editar Carrera</h1>
        </div>

        <form action="{{ route('carreras.update', $carrera->id) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="nombre">Nombre de la Carrera:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $carrera->nombre }}" required>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('carreras.index') }}" class="btn btn-secondary">Volver al Listado de Carreras</a>
        </div>
    </div>
</body>
</html>
