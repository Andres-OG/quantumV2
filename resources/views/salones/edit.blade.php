<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de edición de salones existentes">
    <title>Editar salon</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar salon</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('salones.update', $salon->idSalon) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')

            <label for="nombre">Nombre del salon:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $salon->nombre }}" required>

            <label for="idPiso">Piso:</label>
            <select name="idPiso" id="idPiso" class="form-control" required>
                <option value="">Seleccione una un piso</option>
                @foreach ($pisos as $piso)
                    <option value="{{ $piso->id }}" {{ $piso->id == $salon->idPiso ? 'selected' : '' }}>
                        {{ $piso->numero }}
                    </option>
                @endforeach
            </select>

            <label for="edificio">Edificio:</label>
            <select name="edificio_id" id="edificio_id" class="edificio_id" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mt-3">Actualizar salon</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('salones.index') }}" class="btn btn-secondary">Volver al listado de salones</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al menú</a>
        </div>
    </div>
</body>
</html>
