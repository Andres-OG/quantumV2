<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quantum Innovators')</title>
    
    <!-- Enlace a Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Enlace a CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Componente de navegación -->
    <x-navbar button-text="Regístrate ahora" button-text2="Iniciar sesión" />

     <!-- Sección "Sobre nuestra empresa" -->
     <section class="about-section">
        <div class="about-container">
            <div class="about-text">
                <h1>Sobre nuestra empresa</h1>
                <p>En Quantum Innovators, nos dedicamos a desarrollar soluciones de software innovadoras que transforman la manera en que las empresas gestionan y optimizan sus operaciones. Nuestra misión es crear tecnologías intuitivas, eficientes y escalables que faciliten el crecimiento y el éxito de nuestros clientes. Con un enfoque en la excelencia y la innovación, estamos comprometidos en ofrecer experiencias excepcionales a través de productos que se adaptan a las necesidades cambiantes del mercado.</p>
            </div>
            <div class="about-image">
                <img src="{{ asset('storage/LogoST.png') }}" alt="Equipo de Quantum Innovators">
                <!-- <a href="https://www.flaticon.es/stickers-gratis/trabajo-en-equipo" title="trabajo en equipo stickers">Trabajo en equipo stickers creadas por inipagistudio - Flaticon</a> -->
            </div>
        </div>
    </section>

    <section class="about-details">
        <!-- Bloque de Nuestra Misión -->
        <div class="about-row">
            <div class="about-image">
                <img src="{{ asset('images/mision.svg') }}" alt="Nuestra misión - Quantum Innovators">
            </div>
            <div class="about-text">
                <h2>Nuestra misión: <br> Ayudar a empresas a crecer mejor</h2>
                <p>En Quantum Innovators, creemos en el poder de la tecnología para transformar el crecimiento de las empresas. Nuestra misión es proporcionar soluciones de software que no solo permitan a las empresas crecer más, sino crecer mejor. Ayudamos a nuestros clientes a alcanzar el éxito y, en última instancia, mejorar la experiencia de sus propios clientes.</p>
            </div>
        </div>

        <!-- Bloque de Nuestra Historia -->
        <div class="about-row">
            <div class="about-text">
                <h2>Nuestra historia</h2>
                <p>Desde nuestra fundación, Quantum Innovators ha sido un referente en el desarrollo de software innovador. Nacimos con la visión de revolucionar la forma en que las empresas gestionan sus operaciones, proporcionando herramientas que permitan una administración eficiente, escalable y adaptada a las necesidades actuales del mercado.</p>
                <p>Hoy en día, seguimos comprometidos con la excelencia y trabajamos continuamente para ofrecer tecnologías que permitan a nuestros clientes adaptarse a los cambios del entorno digital y maximizar su potencial de crecimiento.</p>
            </div>
            <div class="about-image">
                <img src="{{ asset('images/historia.svg') }}" alt="Nuestra historia - Quantum Innovators">
            </div>
        </div>
    </section>

    <section class="stats-section">
        <h2>Quantum Innovators en cifras</h2>
        <div class="stats-cards">
            <div class="stats-card">
                <img src="{{ asset('images/institucion.svg') }}" alt="Oficinas">
                <h3>0 instituciones registradas</h3>
            </div>
            <div class="stats-card">
                <img src="{{ asset('images/empleados.svg') }}" alt="Empleados">
                <h3>Más de 7 empleados</h3>
            </div>
            <div class="stats-card">
                <img src="{{ asset('images/clientes.svg') }}" alt="Clientes">
                <h3>Más de 0 usuarios</h3>
            </div>
        </div>
    </section>

    <!-- Componente de pie de página -->
    <x-footer />

</body>
</html>
