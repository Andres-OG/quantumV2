<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Horarios por Salón</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Listado de Horarios por Salón</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <script>
            function updateDeleteFormAction(periodo) {
                const form = document.getElementById('deleteByPeriodForm');
                form.action = "{{ url('horarios/periodo') }}/" + periodo;
            }
        </script>

        @foreach ($salones as $salon)
            <h3>Salón: {{ $salon->nombre }}</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'] as $dia)
                                <th>{{ ucfirst($dia) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'] as $dia)
                                <td>
                                    @foreach ($salon->horarios->where('dia', $dia) as $horario)
                                        <div class="horario-item">
                                            <p><strong>Materia-Grupo:</strong> {{ $horario->grupo->materia->nombre }} - {{ $horario->grupo->nombre }}</p>
                                            <p><strong>Maestro:</strong> {{ $horario->maestro->nombre }}</p>
                                            <p><strong>Horario:</strong> {{ $horario->horaInicio }} - {{ $horario->horaFin }}</p>

                                            <!-- Botones de Editar y Eliminar -->
                                            <div class="action-buttons">
                                                <a href="{{ route('horarios.edit', $horario->idHorario) }}" class="btn btn-warning">Editar</a>
                                                <form action="{{ route('horarios.destroy', $horario->idHorario) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este horario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                            <hr> <!-- Separador para cada horario -->
                                        </div>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach

        <div class="mt-4">
            <a href="{{ route('horarios.create') }}" class="btn btn-primary">Registrar Nuevo Horario</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
