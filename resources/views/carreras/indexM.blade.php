<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado de Carreras - Gestión de Instituciones Educativas">
    <title>Listado de Carreras</title>
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
            <h1>Tus carreras</h1>
            <div>
                <h2>{{ $nombre }}</h2>
            </div>
        </div>

        <div class="title">
            <h1>Listado de Carreras</h1>
        </div>

        <ul class="listado-carreras">
            @foreach ($carreras as $carrera)
                <li class="carrera-item">
                    <span class="carrera-nombre">{{ $carrera->nombre }}</span>
                    <!-- Corregir la ruta de la acción -->
                    <a href="{{ route('carreras.edit', $carrera->id) }}" class="btn btn-edit">Editar</a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <a href="{{ route('carreras.create') }}" class="btn btn-primary">Dar de Alta Carrera</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
