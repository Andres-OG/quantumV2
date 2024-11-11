<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Proceso de edición de salones existentes">
    <title>Editar salón</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Editar salón</h1>
            <div>
                <h2>Bienvenido: {{ $name }}</h2>
                <h2>Institución: {{ $nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('salones.update', $salon->idSalon) }}" method="POST" class="form-group">
            @csrf
            @method('PUT')

            <label for="nombre">Nombre del salón:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $salon->nombre }}" required>
            @error('nombre')
                <div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>
            @enderror

            <label for="edificio_id">Edificio:</label>
            <select name="edificio_id" id="edificio_id" class="form-control" required>
                <option value="">Seleccione un edificio</option>
                @foreach ($edificios as $edificio)
                    <option value="{{ $edificio->id }}" {{ $edificio->id == $salon->piso->edificio_id ? 'selected' : '' }}>
                        {{ $edificio->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="idPiso">Piso:</label>
            <select name="idPiso" id="idPiso" class="form-control" required>
                <option value="">Seleccione un piso</option>
                @foreach ($pisos as $piso)
                    <option value="{{ $piso->id }}" {{ $piso->id == $salon->idPiso ? 'selected' : '' }}>
                        {{ $piso->numero }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-3">Actualizar salón</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('salones.index') }}" class="btn btn-secondary">Volver al listado de salones</a>
        </div>
    </div>

    <script>
        $('#edificio_id').on('change', function() {
            var edificioId = $(this).val();
            $('#idPiso').empty().append('<option value="">Seleccione un piso</option>'); // Limpiar opciones
    
            if (edificioId) {
                $.get('/salones/pisos/' + edificioId, function(data) {
                    console.log(data); // Verifica la respuesta en la consola
                    data.forEach(function(piso) {
                        $('#idPiso').append('<option value="' + piso.id + '">' + piso.numero + '</option>');
                    });
                }).fail(function() {
                    alert('Error al cargar los pisos. Verifica la conexión o la ruta.');
                });
            }
        });
    </script>    
</body>
</html>