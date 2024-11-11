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
            <label for="numero">Número de Piso:</label>
            <select name="numero" id="numero" class="form-control" required>
                <option value="">Seleccione un número de piso</option>
                <option value="PB">PB (Planta Baja)</option>
                @for ($i = 1; $i <= 9; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            @error('numero')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="edificio_id">Edificio:</label>
            <select name="edificio_id" id="edificio_id" class="form-control" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}" {{ old('edificio_id') == $edificio->id ? 'selected' : '' }}>
                        {{ $edificio->nombre }}
                    </option>
                @endforeach
            </select>
            @error('edificio_id')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Registrar Piso</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('pisos.index') }}" class="btn btn-secondary">Volver al listado de pisos</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
