<div class="flex flex-col h-full"
    style="background-color: {{ Auth::user()->institution->ColorP ?? '#2d3748' }}; color: {{ Auth::user()->institution->ColorS ?? '#ffffff' }};">
    <!-- Logo y Título -->
    <div class="flex items-center justify-center py-6 border-b"
        style="border-color: {{ Auth::user()->institution->ColorS ?? '#ffffff' }};">
        <i class="bx bx-shield-quarter text-6xl"></i>
        <p class="ml-3 text-lg font-bold" x-show="sidebarOpen" x-transition>
            {{ Auth::user()->institution->name ?? 'No asignada' }}</p>
    </div>

    <!-- Menú de Navegación -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('institution.dashboard') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('institution.dashboard') ? 'selected' : '' }}"
            style="{{ request()->routeIs('institution.dashboard') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-bar-chart-alt-2 text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Dashboard</span>
        </a>

        <a href="{{ route('carreras.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('carreras.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('carreras.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class='bx bx-math text-xl'></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Carreras</span>
        </a>

        <a href="{{ route('maestros.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('maestros.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('maestros.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-user text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Maestros</span>
        </a>

        <a href="{{ route('materias.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('materias.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('materias.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-book text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Materias</span>
        </a>

        <a href="{{ route('grupos.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('grupos.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('grupos.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class='bx bxs-graduation text-xl'></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Grupos</span>
        </a>

        <a href="{{ route('edificios.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('edificios.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('edificios.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-building text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Edificios</span>
        </a>

        <a href="{{ route('pisos.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('pisos.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('pisos.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-square-rounded text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Pisos</span>
        </a>

        <a href="{{ route('salones.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('salones.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('salones.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-door-open text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Salones</span>
        </a>

        <a href="{{ route('horarios.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('horarios.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('horarios.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-calendar text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Horarios</span>
        </a>

        <a href="{{ route('eventos.gestion') }}"
            class="flex items-center px-3 py-2 rounded transition-colors {{ request()->routeIs('eventos.*') ? 'selected' : '' }}"
            style="{{ request()->routeIs('eventos.*') ? 'background-color: rgba(255, 255, 255, 0.2); color: ' . (Auth::user()->institution->ColorS ?? '#ffffff') . ';' : '' }}">
            <i class="bx bx-calendar-event text-xl"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Eventos</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="mt-auto p-4 border-t" style="border-color: {{ Auth::user()->institution->ColorS ?? '#ffffff' }};">
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button" 
                class="w-full flex items-center px-3 py-2 rounded transition-colors"
                id="logoutButton"
                style="color: {{ Auth::user()->institution->ColorS ?? '#ffffff' }};">
                <i class="bx bx-log-out text-xl"></i>
                <span class="ml-3" x-show="sidebarOpen" x-transition>Cerrar sesión</span>
            </button>
        </form>
    </div>    
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutButton = document.getElementById('logoutButton');
        const logoutForm = document.getElementById('logoutForm');

        logoutButton.addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: '¿Estás seguro de que deseas salir?',
                text: "Se cerrará tu sesión y perderás el acceso temporalmente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    logoutForm.submit(); // Enviar el formulario para cerrar sesión
                }
            });
        });
    });
</script>
