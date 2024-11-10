<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <div class="login-container_image">
            <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo" class="logo">
        </div>
        <div class="login-container_form">
            <h2>Iniciar sesión</h2>
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('login.process') }}" method="POST" class="form-container">
                @csrf
                <input type="email" id="email" name="email" placeholder="Ingresa tu email" class="form-inputs" required>                    

                <!-- Campo de contraseña con icono de ojo dentro -->
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" class="form-inputs" required>
                    <span class="material-icons toggle-password" onclick="togglePasswordVisibility()">visibility</span>
                </div>

                <button type="submit">Iniciar Sesión</button>
                <div class="forgot-password">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
            </form> 
            <div class="register-link">
                <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = 'visibility_off'; // Cambia el icono al de "ocultar"
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = 'visibility'; // Cambia el icono al de "ver"
            }
        }
    </script>
</body>

</html>
