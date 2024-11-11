<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pantalla Principal - Gestión de Instituciones Educativas">
    <title>Pantalla Principal</title>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    
    @php
        $adminLinks = [
            ['route' => 'dashboard', 'icon' => 'bx-home-alt', 'text' => 'Dashboard'],
            ['route' => 'carreras.create', 'icon' => 'bx-book', 'text' => 'Registrar Carrera'],
            ['route' => 'maestros.create', 'icon' => 'bx-user', 'text' => 'Registrar Maestros'],
            // Otros enlaces...
        ];

        $userLinks = [
            ['route' => 'user.profile', 'icon' => 'bx-user', 'text' => 'Perfil'],
            ['route' => 'user.settings', 'icon' => 'bx-cog', 'text' => 'Configuración'],
            ['route' => 'user.notifications', 'icon' => 'bx-bell', 'text' => 'Notificaciones'],
            // Otros enlaces específicos para otro tipo de usuario...
        ];
    @endphp

    <!-- Si es un sidebar para administrador -->
    <x-sidebar :links="$adminLinks" />

    <div class="container">
        <div class="header">
            <h1>Personaliza tu plantel</h1>
            <div class="user-info">
                <h1>Bienvenido: {{ $name }}</h1>
                <h2>Institución: {{ $nameI }}</h2>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 20px;">
                <a href="{{ route('carreras.create') }}" class="btn btn-carrera">Registrar Carrera</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('maestros.create') }}" class="btn btn-secondary">Registrar Maestros</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('materias.create') }}" class="btn btn-secondary">Registrar Materias</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('grupos.create') }}" class="btn btn-secondary">Registrar Grupo</a> 
            <div class="col-md-3">
                <a href="{{ route('edificios.create') }}" class="btn btn-secondary">Registrar Edificio</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('pisos.create') }}" class="btn btn-secondary">Registrar Pisos</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('salones.create') }}" class="btn btn-secondary">Registrar Salon</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('horarios.create') }}" class="btn btn-success">Registrar Horarios</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('eventos.create') }}" class="btn btn-success">Registrar Evento</a>
            </div>
        </div>
    </div>
</body>
</html>
