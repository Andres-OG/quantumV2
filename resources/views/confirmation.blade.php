<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Registro</title>
    <link href="{{ asset('css/confirmation.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="confirmation-container">
        <!-- Icono de éxito -->
        <div class="icon-container">
            <i class="fas fa-check-circle success-icon"></i>
        </div>
        
        <!-- Mensajes de confirmación -->
        <h4>{{ $nameI }}</h4>
        <h2>Tus datos han sido enviados correctamente</h2>
        <p class="description">
            Hemos enviado tus datos al administrador para su revisión. Recibirás un correo de confirmación en breve.
        </p>
        <div>
            
        <p>Puedes esperar mientras abrobamos tu solicitud</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dashboard-button">Ok</button>
            </form>
        </div>

    </div>
</body>
</html>
