<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <script>
        let isComposing = false;

        function validateNameInput(event) {
            if (isComposing) return;

            const input = event.target;

            // Solo permite letras, vocales acentuadas y espacios
            const validValue = input.value.normalize('NFC').replace(/[^a-zA-Z\u00C0-\u017FñÑ\s]/g, '');

            // Limitar la longitud máxima
            input.value = validValue.slice(0, 40);

            if (input.value.length < 3) {
                input.setCustomValidity("El nombre debe tener al menos 3 caracteres.");
            } else {
                input.setCustomValidity(""); 
            }
        }


        document.addEventListener("DOMContentLoaded", () => {
            const nameInputs = document.querySelectorAll("#name, #firstNameMale, #firstNameFemale");

            nameInputs.forEach((input) => {
                // Detectar caracteres combinados (e.g., `´` seguido de vocal)
                input.addEventListener("compositionstart", () => {
                    isComposing = true;
                });

                input.addEventListener("compositionend", () => {
                    isComposing = false;
                    validateNameInput({
                        target: input
                    });
                });

                // Validar en tiempo real mientras se escribe
                input.addEventListener("input", validateNameInput);
            });
        });
    </script>

</head>

<body>
    <div class="register-container" style="width: 60%;">
        <!-- Contenedor de Imagen -->
        <div class="register-container_image">
            <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo de la Institución" class="logo">
        </div>

        <!-- Contenedor del Formulario -->
        <div class="register-container_form">
            <h2>Registro de Administrador para la Institución</h2>
            <p style="margin-bottom: 4rem; text-align: center; font-size: 1.2rem">
                Por favor, llena los campos para registrar un nuevo administrador.
            </p>

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

                <div class="form-columns">
                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu nombre" type="text" id="name" name="name"
                            value="{{ old('name') }}" maxlength="40" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu primer apellido" type="text" id="firstNameMale"
                            name="firstNameMale" value="{{ old('firstNameMale') }}" maxlength="40" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu segundo apellido" type="text"
                            id="firstNameFemale" name="firstNameFemale" value="{{ old('firstNameFemale') }}"
                            maxlength="40" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu email" type="email" id="email" name="email"
                            value="{{ old('email') }}" maxlength="100" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Ingresa tu contraseña" type="password" id="password"
                            name="password" required>
                    </div>

                    <div class="form-group">
                        <input class="form-input" placeholder="Confirma tu contraseña" type="password"
                            id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <button type="submit">Registrar Administrador</button>
            </form>

            <div class="login-link">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </div>
</body>

</html>