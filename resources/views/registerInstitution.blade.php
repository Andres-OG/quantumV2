<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Institución</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">

    <script>
        let isComposing = false; // Indica si se está escribiendo un carácter combinado

        function validateInstitutionInput(event) {
            if (isComposing) return;

            const input = event.target;

            const regex = /^[a-zA-Z\u00C0-\u017FñÑ\s]*$/;

            let validValue = input.value.normalize('NFC').replace(/[^a-zA-Z\u00C0-\u017FñÑ\s]/g, '');

            // Validar que tenga al menos 2 caracteres
            if (validValue.length < 2) {
                input.setCustomValidity("El nombre debe tener al menos 2 caracteres.");
            } else {
                input.setCustomValidity(""); // Restablece el estado válido
            }

            input.value = validValue.slice(0, 100);
        }


        document.addEventListener("DOMContentLoaded", () => {
            const input = document.getElementById("institucion");

            input.addEventListener("compositionstart", () => {
                isComposing = true;
            });

            input.addEventListener("compositionend", () => {
                isComposing = false;
                validateInstitutionInput({
                    target: input
                });
            });

            input.addEventListener("input", validateInstitutionInput);
        });
    </script>
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

            <form action="{{ route('institution.store') }}" method="POST"
                style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                @csrf
                <input type="text" id="institucion" name="institucion"
                    placeholder="Ingresa el nombre de tu institución" value="{{ old('institucion') }}" required
                    maxlength="100">
                    <div style="display: flex; flex-direction: column; width: 100%; padding: 0rem 4rem; margin-left: 2rem;">
                        <h3 style="margin: 0.5rem;">Elige tus colores</h3>
                        <p style="margin: 0.5rem;">Personaliza tu experiencia eligiendo los colores de tu institución:</p>
                    </div>
                    <div class="flex flex-wrap gap-6 mb-6 w-full max-w-4xl mx-auto">
                        <!-- Contenedor para Color de Fondo -->
                        <div class="w-full lg:w-1/2 p-6 bg-white rounded-lg shadow-xl border border-gray-200 hover:shadow-2xl transition duration-300">
                            <label for="colorFondo" class="block text-gray-700 font-semibold mb-3">Color de Fondo:</label>
                            <input type="color" id="colorFondo" name="ColorP" value="#2d3748" class="w-full h-16 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        </div>

                        <!-- Contenedor para Color del Texto -->
                        <div class="w-full lg:w-1/2 p-6 bg-white rounded-lg shadow-xl border border-gray-200 hover:shadow-2xl transition duration-300">
                            <label for="colorTexto" class="block text-gray-700 font-semibold mb-3">Color del Texto:</label>
                            <input type="color" id="colorTexto" name="ColorS" value="#ffffff" class="w-full h-16 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        </div>
                    </div>
                <button type="submit">Continuar al Pago</button>
            </form>

            <div class="login-link">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </div>

            <div class="privacy-policy">
                <h3>Aviso de Privacidad</h3>
                <p>
                    Al registrarte, aceptas nuestro
                    <a href="{{ route('privacy.policy') }}">avisos de privacidad</a>.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
