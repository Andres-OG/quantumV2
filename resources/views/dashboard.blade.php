<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard de Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery y DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    @livewireStyles
</head>
<body>
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Contenedor Principal -->
    <div class="container">
        <h2>Dashboard de Administrador</h2>
        
        <div class="user-info">
            <p>Bienvenido, {{ session('usuario_name') }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Tabla de Usuarios -->
        <div class="table-container">
            <table id="usersTable" class="display styled-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id_user }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->id_role != 1) 
                                    <form method="POST" action="{{ route('admin.user.status.update', $user->id_user) }}">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="1" {{ $user->status ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ !$user->status ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if ($user->id_role != 1) 
                                    <form action="{{ route('admin.user.delete', $user->id_user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    @livewireScripts
    <script>
        // Script de configuración del sidebar
        const body = document.querySelector('body'),
              sidebar = body.querySelector('nav'),
              toggle = body.querySelector(".toggle"),
              modeSwitch = body.querySelector(".toggle-switch"),
              modeText = body.querySelector(".mode-text");

        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");
            modeText.innerText = body.classList.contains("dark") ? "Light mode" : "Dark mode";
        });
    </script>

    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "language": {
                    "search": "Buscar:",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros en total)",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "ordering": true,
                "order": [[ 0, "asc" ]]
            });
        });
    </script>
</body>
</html>
