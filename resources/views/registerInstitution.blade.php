<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Institución</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="register-container">
        <!-- Imagen de la izquierda -->
        <div class="register-container_image">
            <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo de Quantum Innovators">
        </div>

        <!-- Formulario de Registro -->
        <div class="register-container_form">
        <h2>Registro de Institución</h2>
            <!-- Mensaje de error si la institución ya existe -->
            @if ($errors->has('institucion'))
                <div class="error-message">
                    {{ $errors->first('institucion') }}
                </div>
            @endif

            <form action="{{ route('institution.store') }}" method="POST" style="width: 100%; display: flex; flex-direction: column;  align-items: center; justify-content: center;">
                @csrf
                <input type="text" id="institucion" name="institucion" placeholder="Ingresa el nombre de tu institución" required>

                <button type="submit">Continuar al Pago</button>
            </form>

            <div class="login-link">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </div>

            <div class="privacy-policy">
                <h3>Aviso de Privacidad</h3>
                <p>
                    Al registrarte, aceptas nuestros <a href="{{ route('privacy.policy') }}">términos de servicio</a> y <a href="{{ route('privacy.policy') }}">avisos de privacidad</a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
