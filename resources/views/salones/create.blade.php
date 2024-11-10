<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de registro de nuevos salones">
    <title>Registro de salon</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registro de nuevo salon</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('salones.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre del salon:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
            @error('nombre')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="piso">Piso:</label>
            <select name="idPiso" id="idPiso" class="form-control" required>
                <option value="">Seleccione un piso</option>
                @foreach ($pisos as $piso)
                    <option value="{{ $piso->id }}">{{ $piso->numero }}</option>
                @endforeach
            </select>

            <label for="edificio">Edificio:</label>
            <select name="edificio_id" id="edificio_id" class="form-control" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mt-3">Registrar salón</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('salones.index') }}" class="btn btn-secondary">Volver al listado de salones</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Volver al menú</a>
        </div>
    </div>
</body>
</html>
