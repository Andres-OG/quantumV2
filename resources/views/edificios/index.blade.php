<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lista de edificios de la institución">
    <title>Listado de edificios</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de edificios</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <ul class="listado-edificios">
            @foreach ($edificios as $edificio)
                <li class="edificio-item">
                    <span class="edificio-nombre">{{ $edificio->nombre }}</span>
                    <a href="{{ route('edificios.edit', $edificio->id) }}" class="btn btn-edit">Editar</a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <a href="{{ route('edificios.create') }}" class="btn btn-primary">Registrar edificio</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al menú</a>
        </div>
    </div>
</body>
</html>
