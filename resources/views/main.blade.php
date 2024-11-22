<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pantalla Principal - Gestión de Instituciones Educativas">
    <title>Pantalla Principal</title>

    <!-- Vincular CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Barra lateral -->
    <x-sidebar />
    <div class="container">
        <div class="content">
            <div class="header">
                <div class="user-info">
                    <h2>{{ $nameI }}</h2>
                </div>
            </div>

            <!-- Contenido desplazable -->
            <div class="main-content">
            <div class="stats-cards">
            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('carreras.create') }}" class="stats-card">
                            <img src="{{ asset('images/institucion.svg') }}" >
                            <h3>Carreras</h3>
                            <h4>10</h4>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('maestros.create') }}" class="stats-card">
                            <img src="{{ asset('images/empleados.svg') }}" alt="Maestros">
                            <h3>Maestros</h3>
                            <h4>10</h4>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('materias.create') }}" class="stats-card">
                            <img src="{{ asset('images/clientes.svg') }}" alt="Materias">
                            <h3>Materias</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('grupos.create') }}" class="stats-card">
                            <img src="{{ asset('images/group.svg') }}" alt="Grupo">
                            <h3>Grupos</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('edificios.create') }}" class="stats-card">
                            <img src="{{ asset('images/building.svg') }}" alt="Edificio">
                            <h3>Edificios</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('pisos.create') }}" class="stats-card">
                            <img src="{{ asset('images/floor.svg') }}" alt="Piso">
                            <h3>Pisos</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('salones.create') }}" class="stats-card">
                            <img src="{{ asset('images/salon.svg') }}" alt="Salón">
                            <h3>Salones</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('horarios.create') }}" class="stats-card">
                            <img src="{{ asset('images/calendar.svg') }}" alt="Horario">
                            <h3>Horarios</h3>
                        </a>
                    </li>
                </ul>  
            </div>

            <div class="stats-card">
                <ul>
                    <li>
                        <a href="{{ route('eventos.create') }}" class="stats-card">
                            <img src="{{ asset('images/event.svg') }}" alt="Evento">
                            <h3>Eventos</h3>
                        </a>
                    </li>
                </ul>  
            </div>

        </div>
            </div>
        </div>
    </div>

    <script>
        // Función para abrir y cerrar el slider (barra lateral)
        function toggleSlider() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html>
