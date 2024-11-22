<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lista de edificios de la institución">
    <title>Listado de edificios</title>
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
            <h1>Listado de edificios</h1>
            <div>
            <h2> {{ $nombre }}</h2>
            </div>
        </div>

        @if($edificios->count() > 0)
    <ul class="listado-edificios">
        @foreach ($edificios as $edificio)
            <li class="edificio-item">
                <span class="edificio-nombre">{{ $edificio->nombre }}</span>
                <a href="{{ route('edificios.edit', $edificio->id) }}" class="btn btn-edit" aria-label="Editar edificio {{ $edificio->nombre }}">Editar</a>
            </li>
        @endforeach
    </ul>
@else
    <p>No hay edificios registrados en este momento.</p>
@endif


        <div class="mt-4">
            <a href="{{ route('edificios.create') }}" class="btn btn-primary">Registrar edificio</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al menú</a>
        </div>
    </div>
</body>
</html>
