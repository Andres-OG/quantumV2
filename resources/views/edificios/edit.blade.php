<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de edición de edificios existentes">
    <title>Editar edificio</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <!-- Vincular CSS -->
  <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <!-- Barra lateral -->
  <x-sidebar />
    <div class="container">
        <div class="header">
            <h1>Editar edificio</h1>
            <div>
               <h2> {{ $name }}</h2>
            </div>
        </div>

        <form action="{{ route('edificios.update', $edificio->id) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="nombre">Nombre del edificio:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $edificio->nombre) }}" required>

            @error('nombre')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('edificios.vista') }}" class="btn btn-secondary">Volver al listado de edificios</a>
        </div>
    </div>
</body>
</html>
