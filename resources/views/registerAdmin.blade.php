<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
</head>
<body>

    <div class="register-container" style="width: 60%;">
        <!-- Contenedor de Imagen a la Izquierda -->
        <div class="register-container_image">
            <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo de la Institución" class="logo">
        </div>

        <!-- Contenedor del Formulario de Registro -->
        <div class="register-container_form">
            <div>
            <h2>Registro de Administrador para la Institución</h2>
            <p style="margin-bottom: 4rem; text-align: center; font-size: 1.2rem">Por favor, llena los campos para registrar un nuevo administrador.</p>

            </div>
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.store') }}" method="POST" style="width: 90%;">
                @csrf
                <input type="hidden" name="id_institution" value="{{ $id_institution }}">

                <!-- Contenedor de dos columnas para los campos de entrada -->
                <div class="form-columns">
                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu nombre" type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu primer apellido"  type="text" id="firstNameMale" name="firstNameMale" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input"  type="text" placeholder="Ingresa tu segundo apellido" id="firstNameFemale" name="firstNameFemale" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu email" type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu contraseña" type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                    <input class="form-input" placeholder="Confirma tu contraseña" type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <button type="submit">Registrar Administrador</button>
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
