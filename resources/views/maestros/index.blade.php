<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado de Maestros - Gestión de Instituciones Educativas">
    <title>Listado de Maestros</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de Maestros</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <ul class="listado-maestros">
            @foreach ($maestros as $maestro)
                <li class="maestro-item">
                    <span>{{ $maestro->nombre }} - {{ $maestro->carrera->nombre }}</span>

                    <!-- Contenedor para los botones en línea -->
                    <div class="btn-group">
                        <a href="{{ route('maestros.edit', $maestro->id) }}" class="btn btn-edit">Editar</a>

                        <!-- Botón de eliminar -->
                        <form action="{{ route('maestros.destroy', $maestro->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete" onclick="confirmDeletion(this)">Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <a href="{{ route('maestros.create') }}" class="btn btn-primary">Registrar Maestro</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>

    <script>
        function confirmDeletion(button) {
            if (confirm('¿Estás seguro de que deseas eliminar este maestro? Esta acción no se puede deshacer.')) {
                button.closest('form').submit();
            }
        }
    </script>
</body>
</html>
