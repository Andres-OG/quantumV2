<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Piso - Gestión de Instituciones Educativas">
    <title>Registrar Piso</title>
    <!-- Vincular CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- Barra lateral -->
    <x-sidebar />

    <div class="container">
        <div class="header">
            <h1>Registrar nuevo piso</h1>
            <div>
                <h2> {{ $nombre }}</h2>
            </div>
        </div>

        <!-- Mensajes de éxito o error -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Formulario de registro de piso -->
        <form action="{{ route('pisos.store') }}" method="POST" class="form-group">
            @csrf

            <!-- Campo número de piso -->
            <label for="numero">Número de Piso:</label>
<select name="numero" id="numero" class="form-control" required>
    <option value="">Seleccione un número de piso</option>
    <option value="PB" {{ old('numero') == 'PB' ? 'selected' : '' }}>PB (Planta Baja)</option>
    @for ($i = 1; $i <= 9; $i++)
        <option value="{{ $i }}" {{ old('numero') == $i ? 'selected' : '' }}>{{ $i }}</option>
    @endfor
</select>
@error('numero')
    <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
@enderror

            <!-- Campo Edificio -->
            <label for="idEdificio">Edificio:</label>
            <select name="idEdificio" id="idEdificio" class="form-control" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}" {{ old('idEdificio') == $edificio->id ? 'selected' : '' }}>
                        {{ $edificio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('idEdificio')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <!-- Mostrar errores generales -->
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Botón para registrar piso -->
            <button type="submit" class="btn btn-primary mt-3">Registrar Piso</button>
        </form>

        <!-- Volver al listado de pisos -->
        <div class="mt-4">
            <a href="{{ route('pisos.vista') }}" class="btn btn-secondary">Volver al listado de pisos</a>
        </div>
    </div>
</body>

</html>
