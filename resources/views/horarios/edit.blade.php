<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Horario</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body>
  <!-- Barra lateral -->
  <x-sidebar />
    <div class="container">
        <div class="header">
            <h1>Editar Horario</h1>
            <div>
            <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('horarios.update', $horario->idHorario) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')
            
            <label for="horaInicio">Hora de inicio:</label>
            <input type="time" name="horaInicio" id="horaInicio" class="form-control" required value="{{ old('horaInicio', $horario->horaInicio) }}">
            @error('horaInicio')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="horaFin">Hora de fin:</label>
            <input type="time" name="horaFin" id="horaFin" class="form-control" required value="{{ old('horaFin', $horario->horaFin) }}">
            @error('horaFin')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="dia">Día:</label>
            <select name="dia" id="dia" class="form-control" required>
                <option value="">Seleccione un día</option>
                <option value="lunes" {{ old('dia', $horario->dia) == 'lunes' ? 'selected' : '' }}>Lunes</option>
                <option value="martes" {{ old('dia', $horario->dia) == 'martes' ? 'selected' : '' }}>Martes</option>
                <option value="miércoles" {{ old('dia', $horario->dia) == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
                <option value="jueves" {{ old('dia', $horario->dia) == 'jueves' ? 'selected' : '' }}>Jueves</option>
                <option value="viernes" {{ old('dia', $horario->dia) == 'viernes' ? 'selected' : '' }}>Viernes</option>
                <option value="sábado" {{ old('dia', $horario->dia) == 'sábado' ? 'selected' : '' }}>Sábado</option>
            </select>
            @error('dia')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="idGrupo">Grupo:</label>
            <select name="idGrupo" id="idGrupo" class="form-control" required>
                <option value="">Seleccione un grupo</option>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->idGrupo }}" {{ old('idGrupo', $horario->idGrupo) == $grupo->idGrupo ? 'selected' : '' }}>
                        {{ $grupo->nombre }} - {{ $grupo->materia->nombre }}
                    </option>
                @endforeach
            </select>
            @error('idGrupo')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="idMaestro">Maestro:</label>
            <select name="idMaestro" id="idMaestro" class="form-control" required>
                <option value="">Seleccione un maestro</option>
                @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}" {{ old('idMaestro', $horario->idMaestro) == $maestro->id ? 'selected' : '' }}>
                        {{ $maestro->nombre }}
                    </option>
                @endforeach
            </select>
            @error('idMaestro')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="idSalon">Salón:</label>
            <select name="idSalon" id="idSalon" class="form-control" required>
                <option value="">Seleccione un salón</option>
                @foreach ($salones as $salon)
                    <option value="{{ $salon->idSalon }}" {{ old('idSalon', $horario->idSalon) == $salon->idSalon ? 'selected' : '' }}>
                        {{ $salon->nombre }}
                    </option>
                @endforeach
            </select>
            @error('idSalon')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="periodo">Periodo:</label>
            <input type="text" name="periodo" id="periodo" class="form-control" required value="{{ old('periodo', $horario->periodo) }}">
            @error('periodo')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Actualizar Horario</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('horarios.vista') }}" class="btn btn-secondary">Volver al Listado de Horarios</a>
        </div>
    </div>
</body>
</html>

