<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edición de horarios">
    <title>Editar Horario</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar Horario</h1>
            <div>
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $institutionName }}</h2>
            </div>
        </div>

        <form action="{{ route('horarios.update', $horario->idHorario) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            <label for="horaInicio">Hora de Inicio:</label>
            <input type="text" name="horaInicio" id="horaInicio" class="form-control" required placeholder="Ejemplo: 08:00" value="{{ $horario->horaInicio }}">

            <label for="horaFin">Hora de Fin:</label>
            <input type="text" name="horaFin" id="horaFin" class="form-control" required placeholder="Ejemplo: 10:00" value="{{ $horario->horaFin }}">

            <label for="dia">Día:</label>
            <input type="text" name="dia" id="dia" class="form-control" required placeholder="Ejemplo: Lunes" value="{{ $horario->dia }}">

            <label for="idMaestro">Maestro:</label>
            <select name="idMaestro" id="idMaestro" class="form-control" required>
                <option value="">Seleccione un maestro</option>
                @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}" {{ $horario->idMaestro == $maestro->id ? 'selected' : '' }}>
                        {{ $maestro->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="idGrupo">Grupo:</label>
            <select name="idGrupo" id="idGrupo" class="form-control" required>
                <option value="">Seleccione un grupo</option>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ $horario->idGrupo == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="idSalon">Salón:</label>
            <select name="idSalon" id="idSalon" class="form-control" required>
                <option value="">Seleccione un salón</option>
                @foreach ($salones as $salon)
                    <option value="{{ $salon->id }}" {{ $horario->idSalon == $salon->id ? 'selected' : '' }}>
                        {{ $salon->nombre }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Actualizar horario</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('horarios.index') }}" class="btn btn-secondary">Volver al listado de horarios</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al menú</a>
        </div>
    </div>
</body>
</html>
