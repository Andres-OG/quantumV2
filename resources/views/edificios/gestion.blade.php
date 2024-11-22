@extends('main.layoutMain')

@section('title', 'Gestión de Edificios')

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
        <h1 class="text-2xl font-bold">Gestión de Edificios</h1>
        <p class="mb-4">Administra los edificios de tu institución. Puedes agregar, editar y eliminar edificios.</p>
    </div>

    <button onclick="abrirModal()" class="bg-green-500 text-white px-4 h-10 rounded hover:bg-green-600 flex items-center gap-2">
        <i class='bx bx-plus text-xl'></i>
        <span>Agregar Edificio</span>
    </button>
</div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table id="tablaEdificios" class="min-w-full divide-y divide-gray-200 rounded-lg">
                    <thead class="bg-gray-100 rounded-t-lg">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                                Nombre
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($edificios as $edificio)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $edificio->nombre }}</td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex gap-2">
                                    <button
                                        onclick="editarEdificio(`{{ $edificio->id }}`, `{{ addslashes($edificio->nombre) }}`)"
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

    <div id="modalFormulario" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white w-11/12 md:w-1/3 lg:w-1/4 xl:w-1/4 rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0"
        style="animation: fadeIn 0.3s forwards;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start space-x-2">
                <div class="flex gap-2">
                    <i class='bx bx-building-house text-3xl text-blue-500'></i>
                    <h2 id="modalTitulo" class="text-xl font-bold text-gray-700">Agregar Edificio</h2>
                </div>

                <p class="text-sm text-slate-500">Ingrese los detalles del nuevo edificio aquí. Haga clic en guardar
                    cuando termine.</p>

            </div>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEdificio" method="POST" action="{{ route('edificios.store') }}">
            @csrf
            <input type="hidden" name="id_institution" value="{{ Auth::user()->id_institution }}">
            <input type="hidden" id="edificioId" name="id" value="">
            <div class="mb-4">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del edificio"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required minlength="1" maxlength="15" pattern="^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$"
                    onkeydown="validateBuildingNameInput(event)"
                    title="El nombre solo puede contener letras y espacios, y debe tener entre 1 y 15 caracteres.">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit" id="btnGuardar"
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
        document.getElementById('modalFormulario').classList.remove('hidden');
    }

    function cerrarModal() {
        document.getElementById('modalFormulario').classList.add('hidden');
        limpiarFormulario();  // Reset the form when closing the modal
    }

    function limpiarFormulario() {
        const form = document.getElementById('formEdificio'); 
        form.reset();  // Reset the form fields
        const input = document.getElementById('nombre');
        input.value = '';  // Clear the input manually if needed
        document.getElementById('formEdificio').action = '/edificios'; // Reset form action to default
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove(); // Remove the PUT method if exists
        }
        document.getElementById('modalTitulo').innerText = 'Agregar Edificio';  // Reset the modal title
    }
        function editarEdificio(id, nombre) {
            document.getElementById('modalTitulo').innerText = 'Editar Edificio';
            document.getElementById('formEdificio').action = `/edificios/${id}`;
            document.getElementById('formEdificio').innerHTML += `<input type="hidden" name="_method" value="PUT">`;
            document.getElementById('nombre').value = nombre;
            abrirModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('#tablaEdificios').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Buscar edificio...",
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

        function validateBuildingNameInput(event) {
            const char = event.key; 
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
            ];

            const isValidChar = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/.test(char);

            if (!isValidChar && !allowedKeys.includes(char)) {
                event.preventDefault(); 
            }
        }
    </script>
    <style>
        /* Animación para el modal */
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
