<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Editar Carrera - Gestión de Instituciones Educativas">
    <title>Editar Carrera</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <!-- Vincular CSS -->
  <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <!-- Barra lateral -->
  <x-sidebar />
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Editar Carrera</h1>
            <div>
                <h2>{{ $nombre }}</h2> <!-- Muestra el nombre de la institución -->
            </div>
        </div>

        <!-- Título principal -->
        <div class="title">
            <h1>Editar Carrera: {{ $carrera->nombre }}</h1> <!-- Muestra el nombre de la carrera que se está editando -->
        </div>

        <!-- Formulario de edición -->
        <form action="{{ route('carreras.update', $carrera->id) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="nombre">Nombre de la Carrera:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $carrera->nombre) }}" required>

            <x-messages/>
            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>

        <!-- Enlace para volver al listado de carreras -->
        <div class="mt-4">
            <a href="{{ route('carreras.vista') }}" class="btn btn-secondary">Volver al Listado de Carreras</a>
        </div>
    </div>
</body>
</html>
