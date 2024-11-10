<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de registro de nuevos horarios">
    <title>Registro de Horario</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registro de nuevo horario</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('horarios.store') }}" method="POST" class="form-group">
            @csrf
            <label for="horaInicio">Hora de inicio:</label>
            <input type="text" name="horaInicio" id="horaInicio" class="form-control" required placeholder="Ejemplo: 08:00">

            <label for="horaFin">Hora de fin:</label>
            <input type="text" name="horaFin" id="horaFin" class="form-control" required placeholder="Ejemplo: 10:00">

            <label for="dia">Día:</label>
            <input type="text" name="dia" id="dia" class="form-control" required placeholder="Ejemplo: Lunes">

            <label for="maestro">Maestro:</label>
            <select name="idMaestro" id="idMaestro" class="form-control" required>
                <option value="">Seleccione un maestro</option>
                @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}">{{ $maestro->nombre }}</option>
                @endforeach
            </select>

            <label for="grupo">Grupo:</label>
            <select name="idGrupo" id="idGrupo" class="form-control" required>
                <option value="">Seleccione un grupo</option>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>

            <label for="salon">Salón:</label>
            <select name="idSalon" id="idSalon" class="form-control" required>
                <option value="">Seleccione un salón</option>
                @foreach ($salones as $salon)
                    <option value="{{ $salon->id }}">{{ $salon->nombre }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Registrar horario</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('horarios.index') }}" class="btn btn-secondary">Volver al Listado de Horarios</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
