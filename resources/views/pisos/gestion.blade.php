@extends('main.layoutMain')

@section('title', 'Gestión de Pisos')

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
            <h1 class="text-2xl font-bold">Gestión de Pisos</h1>
            <p class="text-gray-500">Administra los pisos de los edificios. Agrega, edita o elimina pisos fácilmente.</p>
        </div>

        <button onclick="abrirModal()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center gap-2">
            <i class='bx bx-plus text-xl'></i>
            <span>Agregar Piso</span>
        </button>
    </div>

    <div class="mb-4 flex flex-col flex-wrap gap-4">
        <h2 class="text-xl font-bold text-gray-700 ">FILTROS</h2>
        <!-- Filtro por carrera -->
        <select id="filtroEdificio" name="filtroEdificio"
            class="w-full sm:w-1/2 md:w-1/4 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los edificios</option>
            @foreach ($edificios as $edificio)
            <option value="{{ $edificio->nombre }}">{{ $edificio->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white p-6 mt-4 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table id="tablaPisos" class="min-w-full divide-y divide-gray-200 rounded-lg border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Número
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Edificio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($pisos as $piso)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $piso->numero }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $piso->edificio->nombre ?? 'Sin edificio' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 flex space-x-2">
                            <button
                                onclick="editarPiso(`{{ $piso->id }}`, `{{ $piso->numero }}`, `{{ $piso->idEdificio }}`)"
                                class="text-blue-500 hover:text-blue-600">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div id="modalFormulario" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0 modal-content"
            style="animation: fadeIn 0.3s forwards;">
            <div class="flex justify-between items-center mb-4">
                <div class="flex flex-col items-start space-x-2">
                    <div class="flex gap-2">
                        <i class='bx bx-layer text-3xl text-blue-500'></i>
                        <h2 id="modalTitulo" class="text-xl font-bold">Agregar Piso</h2>
                    </div>
                    <p class="text-sm text-slate-500">Ingrese los detalles del nuevo piso aquí. Haga clic en guardar
                        cuando termine.</p>
                </div>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 transition">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <form id="formPiso" method="POST" action="{{ route('pisos.store') }}" data-store-action="{{ route('pisos.store') }}">
                @csrf
                <input type="hidden" id="pisoId" name="id" value="">
                <div class="mb-4">
                    <select id="numero" name="numero"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required
                        onchange="validateFloorNumber(this)" title="Seleccione un número de piso válido.">
                        <option value="">Seleccione un número de piso</option>
                        <option value="PB">PB (Planta Baja)</option>
                        @for ($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <div class="mb-4">
                    <select id="idEdificio" name="idEdificio"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccione un edificio</option>
                        @foreach ($edificios as $edificio)
                        <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Guardar
                    </button>
                    <button type="button" onclick="cerrarModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function abrirModal() {
            const modal = document.getElementById('modalFormulario');
            modal.classList.remove('hidden');
            modal.querySelector('.modal-content').classList.remove('scale-90', 'opacity-0');
        }

        function cerrarModal() {
            const modal = document.getElementById('modalFormulario');
            modal.classList.add('hidden');
            modal.querySelector('.modal-content').classList.add('scale-90', 'opacity-0');
            limpiarFormulario();
        }

        function editarPiso(id, numero, idEdificio) {
            const form = document.getElementById('formPiso');

            document.getElementById('pisoId').value = id;
            document.getElementById('numero').value = numero;
            document.getElementById('idEdificio').value = idEdificio;

            form.action = `/pisos/${id}`;

            if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            console.log(form);
            document.getElementById('modalTitulo').innerText = 'Editar Piso';

            abrirModal();
        }

        function limpiarFormulario() {
            const form = document.getElementById('formPiso');
            form.action = form.dataset.storeAction;

            document.getElementById('pisoId').value = '';
            document.getElementById('numero').value = '';
            document.getElementById('idEdificio').value = '';

            // Eliminar el método PUT si existe
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            document.getElementById('modalTitulo').innerText = 'Agregar Piso';
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar DataTable
            const table = $('#tablaPisos').DataTable({
                responsive: true,
                dom: '<"flex justify-between items-center mb-4"lf>tip',
                language: {
                    search: "",
                    searchPlaceholder: "Buscar piso...",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _PAGE_ de _PAGES_ páginas",
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

            document.getElementById('filtroEdificio').addEventListener('change', function() {
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

        function validateFloorNumber(select) {
            const value = select.value;
            const isValid = /^(PB|[1-9])$/.test(value);

            if (!isValid) {
                alert("Seleccione un número de piso válido (PB o del 1 al 9).");
                select.value = "";
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

        .modal-content {
            transform: scale(0.9);
            opacity: 0;
            animation: fadeIn 0.3s forwards;
        }
    </style>

    @endsection