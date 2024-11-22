<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones</title>
   <!-- Enlace a Google Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Enlace a CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body class="bg-light-color text-dark-color">
    <!-- Componente de navegación -->
    <x-navbar button-text="Regístrate ahora" button-text2="Iniciar sesión" />

    <div class="terms-container">
        <h2 class="terms-title">Términos y Condiciones</h2>
        
        <p class="terms-intro">
            Al utilizar este sitio web, aceptas los siguientes términos y condiciones. Si no estás de acuerdo con alguno de estos términos, te recomendamos que no uses este sitio.
        </p>
        
        <div class="terms-content">
            <div class="terms-section">
                <h3 class="terms-section-title">1. Aceptación de los términos</h3>
                <p class="terms-text">
                    Al acceder y utilizar este sitio web, el usuario acepta estos términos y condiciones de manera completa. Si no estás de acuerdo con alguno de los términos aquí descritos, no utilices este sitio web.
                </p>
            </div>

            <div class="terms-section">
                <h3 class="terms-section-title">2. Uso del sitio</h3>
                <p class="terms-text">
                    El usuario se compromete a utilizar este sitio web solo para fines lícitos y no infringir ninguna ley aplicable.
                </p>
            </div>

            <div class="terms-section">
                <h3 class="terms-section-title">3. Propiedad intelectual</h3>
                <p class="terms-text">
                    Todo el contenido en este sitio web, incluidos textos, imágenes, logotipos y marcas, está protegido por derechos de propiedad intelectual. Queda prohibida su reproducción, distribución o uso sin el consentimiento expreso del titular de los derechos.
                </p>
            </div>

            <div class="terms-section">
                <h3 class="terms-section-title">4. Responsabilidad</h3>
                <p class="terms-text">
                    Este sitio web no será responsable de ningún daño directo o indirecto que pueda resultar del uso del sitio. El usuario asume el riesgo de cualquier daño o pérdida que ocurra al utilizar este sitio web.
                </p>
            </div>

            <div class="terms-section">
                <h3 class="terms-section-title">5. Modificaciones</h3>
                <p class="terms-text">
                    Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento. Las modificaciones se publicarán en este sitio, y al continuar utilizando el sitio después de esas modificaciones, el usuario acepta los nuevos términos.
                </p>
            </div>

            <div class="terms-section">
                <h3 class="terms-section-title">6. Ley aplicable</h3>
                <p class="terms-text">
                    Estos términos y condiciones se rigen por las leyes del país donde se encuentra el propietario de este sitio web. Cualquier disputa relacionada con estos términos se resolverá en los tribunales competentes de dicho país.
                </p>
            </div>
        </div>

        <div class="terms-footer">
            <!-- Redirige al registro con la ruta definida en Laravel -->
            <form action="{{ route('register') }}" method="get" class="inline-block mr-4">
                <button type="submit" class="terms-button bg-primary-color text-white py-2 px-6 rounded-lg hover:bg-primary-color-dark transition duration-300">
                    Volver
                </button>
            </form>
        </div>
    </div>
    
</body>
</html>
