<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado de Eventos - Gestión de Instituciones Educativas">
    <title>Listado de Eventos</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de Eventos</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <div class="title">
            <h3>Eventos Registrados</h3>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                    <th>Día</th>
                    <th>Salón</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventos as $evento)
                    <tr>
                        <td>{{ $evento->nombre }}</td>
                        <td>{{ $evento->hora_inicio }}</td>
                        <td>{{ $evento->hora_fin }}</td>
                        <td>{{ $evento->dia }}</td>
                        <td>{{ $evento->salon }}</td>
                        <td>
                            <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <a href="{{ route('eventos.create') }}" class="btn btn-primary">Registrar Evento</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
