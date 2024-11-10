<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Listado de horarios">
    <title>Listado de horarios</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de horarios</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                    <th>Día</th>
                    <th>Maestro</th>
                    <th>Grupo</th>
                    <th>Salón</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $horario)
                    <tr>
                        <td>{{ $horario->horaInicio }}</td>
                        <td>{{ $horario->horaFin }}</td>
                        <td>{{ $horario->dia }}</td>
                        <td>{{ $horario->maestro->nombre ?? 'Maestro no asignado' }}</td>
                        <td>{{ $horario->grupo->nombre ?? 'Grupo no asignado' }}</td>
                        <td>{{ $horario->salon->nombre ?? 'Salón no asignado' }}</td>
                        <td>
                            <a href="{{ route('horarios.edit', $horario->idHorario) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('horarios.destroy', $horario->idHorario) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Esta eguro de que desea eliminar este horario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <a href="{{ route('horarios.create') }}" class="btn btn-primary">Registrar Nuevo Horario</a>
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
