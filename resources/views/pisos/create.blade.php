<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Piso - Gestión de Instituciones Educativas">
    <title>Registrar Piso</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registrar nuevo piso</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <form action="{{ route('pisos.store') }}" method="POST" class="form-group">
            @csrf
            <label for="piso">Número de Piso:</label>
            <input type="text" name="piso" id="piso" class="form-control" required value="{{ old('piso') }}">
            @error('piso')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

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

            <button type="submit" class="btn btn-primary mt-3">Registrar Piso</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('pisos.index') }}" class="btn btn-secondary">Volver al listado de pisos</a>
        </div>
    </div>
</body>
</html>
