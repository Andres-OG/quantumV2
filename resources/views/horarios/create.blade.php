<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de registro de nuevos horarios">
    <title>Registro de Horario</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <!-- Vincular CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body>
  <!-- Barra lateral -->
  <x-sidebar />
  <div class="container">
    <div class="header">
        <h1>Registro de nuevo horario</h1>
        <div>
            <h2>Institución: {{ $nombre }}</h2>
        </div>
    </div>

    <!-- Mostrar el mensaje de error de solapamiento si existe -->
    @if (session('alert'))
        <div class="alert alert-danger">
            {{ session('alert') }}
        </div>
    @endif

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para registro manual -->
    <form action="{{ route('horarios.store') }}" method="POST" class="form-group">
        @csrf
        <label for="horaInicio">Hora de inicio:</label>
        <input type="time" name="horaInicio" id="horaInicio" class="form-control" required value="{{ old('horaInicio') }}">
        @error('horaInicio')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label for="horaFin">Hora de fin:</label>
        <input type="time" name="horaFin" id="horaFin" class="form-control" required value="{{ old('horaFin') }}">
        @error('horaFin')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label for="dia">Día:</label>
        <select name="dia" id="dia" class="form-control" required>
            <option value="">Seleccione un día</option>
            <option value="lunes" {{ old('dia') == 'lunes' ? 'selected' : '' }}>Lunes</option>
            <option value="martes" {{ old('dia') == 'martes' ? 'selected' : '' }}>Martes</option>
            <option value="miércoles" {{ old('dia') == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
            <option value="jueves" {{ old('dia') == 'jueves' ? 'selected' : '' }}>Jueves</option>
            <option value="viernes" {{ old('dia') == 'viernes' ? 'selected' : '' }}>Viernes</option>
            <option value="sábado" {{ old('dia') == 'sábado' ? 'selected' : '' }}>Sábado</option>
        </select>
        @error('dia')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label for="idGrupo">Grupo:</label>
        <select name="idGrupo" id="idGrupo" class="form-control" required>
            <option value="">Seleccione un grupo</option>
            @foreach ($grupos as $grupo)
                <option value="{{ $grupo->idGrupo }}" {{ old('idGrupo') == $grupo->idGrupo ? 'selected' : '' }}>
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
                <option value="{{ $maestro->id }}" {{ old('idMaestro') == $maestro->id ? 'selected' : '' }}>
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
                <option value="{{ $salon->idSalon }}" {{ old('idSalon') == $salon->idSalon ? 'selected' : '' }}>
                    {{ $salon->nombre }}
                </option>
            @endforeach
        </select>
        @error('idSalon')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <!-- Campo para el periodo -->
        <label for="periodo">Periodo:</label>
        <input type="text" name="periodo" id="periodo" class="form-control" required value="{{ old('periodo') }}">
        @error('periodo')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary mt-3">Registrar horario</button>
    </form>

    <!-- Opciones de carga de archivo Excel -->
    <div class="excel-upload mt-4">
        <label for="excelFile">Subir horarios desde Excel:</label>
        <input type="file" id="excelFile" accept=".xlsx, .xls">
        <button onclick="uploadExcel()">Subir y Procesar Excel</button>
        <a href="{{ route('horarios.downloadTemplate') }}" class="btn btn-info">Descargar plantilla de Excel</a>
    </div>

    <div class="mt-4">
        <a href="{{ route('horarios.vista') }}" class="btn btn-secondary">Volver al Listado de Horarios</a>
    </div>
    <div class="mt-4">
        <a href="{{ url('main') }}" class="btn btn-info">Regresar al Menú</a>
    </div>
</div>

<script>
    function uploadExcel() {
        const file = document.getElementById("excelFile").files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: "array" });
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            const json = XLSX.utils.sheet_to_json(sheet, { raw: false });

            // Convertir las horas a un formato adecuado si es necesario
            json.forEach(row => {
                row.horaInicio = formatHour(row.horaInicio);
                row.horaFin = formatHour(row.horaFin);
            });

            // Enviar los datos al servidor
            fetch("{{ route('horarios.uploadExcel') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(json)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert("Horarios subidos exitosamente");
                    location.reload();
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error al subir el archivo");
            });
        };

        reader.readAsArrayBuffer(file);
    }

    function formatHour(time) {
        if (!time) return "00:00"; // Asignar un valor predeterminado si la hora está vacía

        // Intentar convertir el tiempo en distintos formatos
        let date;
        if (typeof time === "string") {
            date = new Date(`1970-01-01T${time}:00`);
        } else if (time instanceof Date) {
            date = time;
        } else {
            return "00:00";
        }

        return date.toISOString().substring(11, 16); // Devuelve hora en formato "HH:MM"
    }
</script>
</body>
</html>
