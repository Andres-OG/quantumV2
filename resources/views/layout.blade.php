<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans w-full" x-data="sidebar('{{ request()->route()->getName() }}')">

    <div class="flex h-screen max-w-full">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
               class="bg-gray-800 text-white flex-shrink-0 transition-all duration-300 md:w-64 sm:w-20 sm:flex-col">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="flex items-center p-3" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="bx bx-shield-quarter text-3xl"></i>
                    <span class="text-lg font-bold ml-3" x-show="showText" x-transition>Admin Panel</span>
                </div>
                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center p-2 rounded hover:bg-gray-700 transition-all duration-300"
                               :class="{'justify-start': sidebarOpen, 'justify-center': !sidebarOpen, 'bg-gray-700': activePage === 'dashboard'}">
                                <i class="bx bx-bar-chart-alt-2 text-xl"></i>
                                <span class="ml-3" x-show="showText" x-transition>Estadísticas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('institutions') }}"
                               class="flex items-center p-2 rounded hover:bg-gray-700 transition-all duration-300"
                               :class="{'justify-start': sidebarOpen, 'justify-center': !sidebarOpen, 'bg-gray-700': activePage === 'institutions'}">
                                <i class="bx bx-building text-xl"></i>
                                <span class="ml-3" x-show="showText" x-transition>Gestión de Instituciones</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- Footer -->
                <div class="p-4 border-t border-gray-700">
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" class="w-full flex items-center p-2 text-white hover:bg-gray-700 rounded transition-all duration-300"
                                :class="sidebarOpen ? 'justify-start' : 'justify-center'" onclick="confirmLogout()">
                            <i class="bx bx-log-out text-xl"></i>
                            <span class="ml-3" x-show="showText" x-transition>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="w-full">
            <!-- Header -->
            <header class="bg-white shadow px-6 py-3 flex items-center justify-between">
                <button @click="toggleSidebar()" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                    <i class="bx bx-menu text-3xl"></i>
                </button>
                <div class="flex flex-col items-end space-x-4">
                    <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-6 flex-1 overflow-auto">
                @yield('content')
                @vite('resources/js/app.js')
            </main>
        </div>
    </div>

    <script>
        function sidebar(currentPage) {
            return {
                sidebarOpen: true,
                showText: true,
                activePage: currentPage,
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    if (!this.sidebarOpen) {
                        this.showText = false;
                    } else {
                        setTimeout(() => {
                            this.showText = true;
                        }, 300); // Esperar la transición
                    }
                }
            };
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se cerrará tu sesión actual.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviamos el formulario
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>

</html>
