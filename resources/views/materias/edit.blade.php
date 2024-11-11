<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edición de Materia - Gestión de Instituciones Educativas">
    <title>Editar Materia</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar Materia</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <form action="{{ route('materias.update', $materia->id) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="nombre">Nombre de la Materia:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $materia->nombre }}" required>

            <label for="carrera_id">Carrera:</label>
            <select name="carrera_id" id="carrera_id" class="form-control" required>
                @foreach ($carreras as $carrera)
                    <option value="{{ $carrera->id }}" {{ $materia->carrera_id == $carrera->id ? 'selected' : '' }}>{{ $carrera->nombre }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver al Listado de Materias</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
