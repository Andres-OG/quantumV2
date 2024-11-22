<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard de Institución')</title>

    <!-- Estilos -->
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

    
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans w-full" x-data="sidebarToggle()">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gray-800 text-white flex-shrink-0 transition-all duration-300">
            @include('components.sidebar') <!-- Reutilizando el componente -->
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow px-6 py-3 flex items-center justify-between">
                <button @click="toggleSidebar()" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                    <i class="bx bx-menu text-3xl"></i>
                </button>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </header>

            <!-- Contenido Principal -->
            <main class="p-6 flex-1 overflow-auto">
                @yield('content') <!-- Contenido Dinámico -->
                @vite('resources/css/app.css')
            </main>
        </div>
    </div>

    <script>
        function sidebarToggle() {
            return {
                sidebarOpen: true, // Estado inicial
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                }
            };
        }
    </script>
</body>

</html>
