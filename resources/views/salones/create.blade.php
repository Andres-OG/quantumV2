<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de registro de nuevos salones">
    <title>Registro de salon</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <!-- Vincular CSS -->
  <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <!-- Barra lateral -->
  <x-sidebar />
    <div class="container">
        <div class="header">
            <h1>Registro de nuevo salón</h1>
            <div>
            <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('salones.store') }}" method="POST" class="form-group">
            @csrf
            <label for="nombre">Nombre del salón:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
            @error('nombre')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="edificio_id">Edificio:</label>
            <select name="edificio_id" id="edificio_id" class="form-control" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                @endforeach
            </select>

            <label for="idPiso">Piso:</label>
            <select name="idPiso" id="idPiso" class="form-control" required>
                <option value="">Seleccione un piso</option>
            </select>
            @error('idPiso')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary mt-3">Registrar salón</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('salones.vista') }}" class="btn btn-secondary">Volver al listado de salones</a>
        </div>
        <div class="mt-4">
            <a href="{{ url('main') }}" class="btn btn-info">Volver al menú</a>
        </div>
    </div>

    <script>
        document.getElementById('edificio_id').addEventListener('change', function() {
            const edificioId = this.value;
            const pisoSelect = document.getElementById('idPiso');

            pisoSelect.innerHTML = '<option value="">Seleccione un piso</option>';

            if (edificioId) {
                fetch(`/pisos-by-edificio/${edificioId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(piso => {
                            const option = document.createElement('option');
                            option.value = piso.id;
                            option.textContent = piso.numero;
                            pisoSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</body>
</html>
