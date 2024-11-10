<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado de Grupos - Gestión de Instituciones Educativas">
    <title>Listado de Grupos</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de Grupos</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <ul class="listado-grupos">
            @foreach ($grupos as $grupo)
                <li class="grupo-item">
                    <span>{{ $grupo->nombre }} - {{ $grupo->materia->nombre }} ({{ $grupo->periodo }})</span>
                    <div class="btn-group">
                        <a href="{{ route('grupos.edit', $grupo->idGrupo) }}" class="btn btn-edit">Editar</a>
                        <form action="{{ route('grupos.destroy', $grupo->idGrupo) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete" onclick="confirmDeletion(this)">Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            <a href="{{ route('grupos.create') }}" class="btn btn-primary">Registrar Grupo</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>

    <script>
        function confirmDeletion(button) {
            if (confirm('¿Estás seguro de que deseas eliminar este grupo? Esta acción no se puede deshacer.')) {
                button.closest('form').submit();
            }
        }
    </script>
</body>
</html>
