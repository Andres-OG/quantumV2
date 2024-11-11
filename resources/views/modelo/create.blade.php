<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registrar Nuevo Evento - Gestión de Instituciones Educativas">
    <title>Registrar Evento</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registrar Nuevo Evento</h1>
            <div>
                <h2>Bienvenido: {{ $name ?? 'Usuario' }}</h2>
                <h2>Institución: {{ $nombre ?? 'Nombre de la Institución' }}</h2>
            </div>
        </div>

        <div class="title">
            <h3>Formulario de Registro de Evento</h3>
        </div>

        <form action="{{ route('eventos.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre del Evento:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="hora_inicio">Hora de Inicio:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required value="{{ old('hora_inicio') }}">
            @error('hora_inicio')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="hora_fin">Hora de Fin:</label>
            <input type="time" name="hora_fin" id="hora_fin" class="form-control" required value="{{ old('hora_fin') }}">
            @error('hora_fin')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="dia">Día:</label>
            <select name="dia" id="dia" class="form-control" required>
                <option value="">Seleccione un día</option>
                <option value="lunes" {{ old('dia') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                <option value="martes" {{ old('dia') == 'martes' ? 'selected' : '' }}>Martes</option>
                <option value="miércoles" {{ old('dia') == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
                <option value="jueves" {{ old('dia') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                <option value="viernes" {{ old('dia') == 'viernes' ? 'selected' : '' }}>Viernes</option>
            </select>
            
            @error('dia')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="salon">Salón:</label>
            <input type="text" name="salon" id="salon" class="form-control" required value="{{ old('salon') }}">
            @error('salon')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Registrar Evento</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Volver al Listado de Eventos</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
