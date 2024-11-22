@extends('main.layoutMain')

@section('title', 'Gestión de Maestros')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container mx-auto p-6">
        @if ($errors->any())
            <div id="errorMessages" class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="rounded-xl font-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div id="successMessage" class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <div class="flex flex-col mb-4 sm:mb-0">
        <h1 class="text-2xl font-bold">Gestión de Maestros</h1>
        <p class="mb-4">Administra los maestros de tu institución. Puedes agregar, editar y gestionar los maestros.</p>
    </div>

    <button onclick="abrirModalAgregar()" class="bg-green-500 text-white px-4 h-10 rounded hover:bg-green-600 flex items-center gap-2">
        <i class='bx bx-plus text-xl'></i>
        <span>Agregar Maestro</span>
    </button>
</div>


        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-700">FILTROS</h2>
                <select id="filtroCarrera"
                    class="mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas las Carreras</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->nombre }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table id="tablaMaestros" class="min-w-full divide-y divide-gray-200 rounded-lg">
                    <thead class="bg-gray-100 rounded-t-lg">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Número de Cuenta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Carrera
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($maestros as $maestro)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $maestro->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $maestro->no_cuenta }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $maestro->carrera->nombre ?? 'Sin carrera' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex gap-2">
                                    <button
                                        onclick="abrirModalEditar('{{ $maestro->id }}', '{{ addslashes($maestro->nombre) }}', '{{ $maestro->no_cuenta }}', '{{ $maestro->carrera_id }}')"
                                        class="text-blue-500 bg-transparent rounded p-1 hover:text-blue-600 hover:bg-blue-100 text-xl">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <form method="POST" action="{{ route('maestros.destroy', $maestro->id) }}"
                                        class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-button text-red-500 text-xl rounded p-1 hover:text-red-600 hover:bg-red-100">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <!-- Modal Agregar -->
<div id="modalAgregar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div
        class="bg-white w-full sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0"
        style="animation: fadeIn 0.3s forwards;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start space-x-2">
                <div class="flex gap-2">
                    <i class='bx bx-user-plus text-3xl text-green-500'></i>
                    <h2 id="modalTituloAgregar" class="text-xl font-bold text-gray-700">Agregar Maestro</h2>
                </div>
                <p class="text-sm text-slate-500">Ingrese los detalles del nuevo maestro aquí. Haga clic en guardar
                    cuando termine.</p>
            </div>
            <button onclick="cerrarModalAgregar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formAgregarMaestro" method="POST" action="{{ route('maestros.store') }}">
            @csrf
            <div class="mb-4">
                <input type="text" id="nombreAgregar" name="nombre" placeholder="Nombre del maestro"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="255" onkeydown="validateNameInput(event)"
                    title="El nombre solo puede contener letras, espacios y caracteres válidos.">
            </div>
            <div class="mb-4">
                <input type="text" id="noCuentaAgregar" name="no_cuenta" placeholder="Número de cuenta"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="7" onkeydown="validateNumberInput(event)"
                    title="El número de cuenta debe contener solo números.">
            </div>
            <div class="mb-4">
                <select id="carreraAgregar" name="carrera_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Seleccione una carrera</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Guardar
                </button>
                <button type="button" onclick="cerrarModalAgregar()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

    </div>


    <!-- Modal Editar -->
    <div id="modalEditar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div
        class="bg-white w-full sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0"
        style="animation: fadeIn 0.3s forwards;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start space-x-2">
                <div class="flex gap-2">
                    <i class='bx bx-user text-3xl text-blue-500'></i>
                    <h2 id="modalTituloEditar" class="text-xl font-bold text-gray-700">Editar Maestro</h2>
                </div>
                <p class="text-sm text-slate-500">Modifique los detalles del maestro aquí. Haga clic en guardar cuando
                    termine.</p>
            </div>
            <button onclick="cerrarModalEditar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEditarMaestro" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <input type="text" id="nombreEditar" name="nombre" placeholder="Nombre del maestro"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="255" onkeydown="validateNameInput(event)"
                    title="El nombre solo puede contener letras, espacios y caracteres válidos.">
            </div>
            <div class="mb-4">
                <input type="text" id="noCuentaEditar" name="no_cuenta" placeholder="Número de cuenta"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="7" onkeydown="validateNumberInput(event)"
                    title="El número de cuenta debe contener solo números.">
            </div>
            <div class="mb-4">
                <select id="carreraEditar" name="carrera_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Seleccione una carrera</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Guardar
                </button>
                <button type="button" onclick="cerrarModalEditar()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

    </div>


    <script>
        function abrirModalAgregar() {
            document.getElementById('modalAgregar').classList.remove('hidden');
        }

        function cerrarModalAgregar() {
            document.getElementById('modalAgregar').classList.add('hidden');
        }

        function abrirModalEditar(id, nombre, noCuenta, carreraId) {
            const form = document.getElementById('formEditarMaestro');
            form.action = `/maestros/${id}`;
            document.getElementById('nombreEditar').value = nombre;
            document.getElementById('noCuentaEditar').value = noCuenta;
            document.getElementById('carreraEditar').value = carreraId;
            document.getElementById('modalEditar').classList.remove('hidden');
        }

        function cerrarModalEditar() {
            document.getElementById('modalEditar').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#tablaMaestros').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Buscar maestro...",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });

            document.getElementById('filtroCarrera').addEventListener('change', function() {
                table.column(2).search(this.value).draw();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = "opacity 0.5s ease";
                    successMessage.style.opacity = 0;

                    setTimeout(() => successMessage.remove(), 500);
                }, 3000);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const errorMessages = document.getElementById('errorMessages');
            if (errorMessages) {
                setTimeout(() => {
                    errorMessages.style.transition = "opacity 0.5s ease";
                    errorMessages.style.opacity = 0;

                    setTimeout(() => errorMessages.remove(), 500);
                }, 3000);
            }
        });

        function validateNameInput(event) {
            const char = event.key;
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
            ];

            const isLetter = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]$/.test(char);

            if (!isLetter && !allowedKeys.includes(char)) {
                event.preventDefault();
            }
        }

        function validateNumberInput(event) {
            const char = event.key;
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
            ];

            const isNumber = /^[0-9]$/.test(char);

            if (!isNumber && !allowedKeys.includes(char)) {
                event.preventDefault();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = this.closest(
                    'form');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); 
                        }
                    });
                });
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection
