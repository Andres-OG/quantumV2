<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Maestro - Gestión de Instituciones Educativas">
    <title>Registrar Maestro</title>
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
            <h1>Registrar Nuevo Maestro</h1>
            <br>
            <h2>{{ $nombre }}</h2>
        </div>
        
        <form action="{{ route('maestros.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre del Maestro:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="no_cuenta">Número de Cuenta:</label>
            <input type="text" name="no_cuenta" id="no_cuenta" value="{{ old('no_cuenta') }}" maxlength="7" required>
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
            <a href="{{ route('maestros.vista') }}" class="btn btn-secondary">Volver al Listado de Maestros</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
    
    <script>
        // Función para abrir y cerrar el slider (barra lateral)
        function toggleSlider() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html>
