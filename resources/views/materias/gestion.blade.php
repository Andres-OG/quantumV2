@extends('main.layoutMain')

@section('title', 'Gestión de Materias')

@section('content')
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
            <h1 class="text-2xl font-bold">Gestión de Materias</h1>
            <p class="mb-4">Administra las materias de tu institución. Puedes agregar, editar y gestionar las materias.</p>
        </div>

        <button onclick="abrirModalAgregar()" class="bg-green-500 text-white px-4 h-10 rounded hover:bg-green-600 flex items-center gap-2">
            <i class='bx bx-plus text-xl'></i>
            <span>Agregar Materia</span>
        </button>
    </div>

    <div class="mb-4 flex flex-col flex-wrap gap-4">
        <h2 class="text-xl font-bold text-gray-700 ">FILTROS</h2>
        <!-- Filtro por carrera -->
        <select id="filtroCarrera"
            class="w-full sm:w-1/2 md:w-1/4 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todas las Carreras</option>
            @foreach ($carreras as $carrera)
            <option value="{{ $carrera->nombre }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">

        <div class="overflow-x-auto">
            <table id="tablaMaterias" class="min-w-full divide-y divide-gray-200 rounded-lg">
                <thead class="bg-gray-100 rounded-t-lg">
                    <tr>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                            Nombre
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Carrera
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($materias as $materia)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $materia->nombre }}</td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $materia->carrera->nombre ?? 'Sin carrera' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex flex-wrap gap-2">
                            <button
                                onclick="abrirModalEditar('{{ $materia->id }}', '{{ $materia->nombre }}', `{{ $materia->carrera->id ?? '' }}`)"
                                class="text-blue-500 bg-transparent rounded p-1 hover:text-blue-600 hover:bg-blue-100 text-xl">
                                <i class='bx bx-edit'></i>
                            </button>
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
        class="bg-white w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0"
        style="animation: fadeIn 0.3s forwards;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start">
                <div class="flex gap-2">
                    <i class='bx bx-book text-3xl text-green-500'></i>
                    <h2 id="modalTituloAgregar" class="text-xl font-bold text-gray-700">Agregar Materia</h2>
                </div>
                <p class="text-sm text-slate-500 mt-2">
                    Ingrese los detalles de la nueva materia aquí. Haga clic en guardar cuando termine.
                </p>
            </div>
            <button onclick="cerrarModalAgregar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formAgregarMateria" method="POST" action="{{ route('materias.store') }}">
            @csrf
            <div class="mb-4">
                <input type="text" id="nombreAgregar" name="nombre" placeholder="Nombre de la materia"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="100" pattern="^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$"
                    onkeydown="validateMateriaNameInput(event)"
                    title="El nombre solo debe contener letras, números y espacios.">
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

<!-- Modal Editar -->
<div id="modalEditar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div
        class="bg-white w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0"
        style="animation: fadeIn 0.3s forwards;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start">
                <div class="flex gap-2">
                    <i class='bx bx-book text-3xl text-blue-500'></i>
                    <h2 id="modalTituloEditar" class="text-xl font-bold text-gray-700">Editar Materia</h2>
                </div>
                <p class="text-sm text-slate-500 mt-2">
                    Modifique los detalles de la materia aquí. Haga clic en guardar cuando termine.
                </p>
            </div>
            <button onclick="cerrarModalEditar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEditarMateria" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="idEditar" name="id">
            <div class="mb-4">
                <input type="text" id="nombreEditar" name="nombre" placeholder="Nombre de la materia"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="100" pattern="^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$"
                    onkeydown="validateMateriaNameInput(event)"
                    title="El nombre solo debe contener letras, números y espacios.">
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

    function abrirModalEditar(id, nombre, carrera_id) {
        const form = document.getElementById('formEditarMateria');
        form.action = `/materias/${id}`;
        document.getElementById('idEditar').value = id;
        document.getElementById('nombreEditar').value = nombre;
        document.getElementById('carreraEditar').value = carrera_id;
        document.getElementById('modalEditar').classList.remove('hidden');
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#tablaMaterias').DataTable({
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Buscar materia...",
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
            table.column(1).search(this.value).draw();
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

    function validateMateriaNameInput(event) {
        const char = event.key;
        const allowedKeys = [
            'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
        ];

        const isValidChar = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]$/.test(char);

        if (!isValidChar && !allowedKeys.includes(char)) {
            event.preventDefault();
        }
    }
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