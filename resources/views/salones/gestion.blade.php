@extends('main.layoutMain')

@section('title', 'Gestión de Salones')

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
                <h1 class="text-2xl font-bold">Gestión de Salones</h1>
                <p class="text-gray-500">Administra los salones de los pisos. Agrega, edita o elimina salones fácilmente.</p>
            </div>
            
            <button onclick="abrirModalAgregar()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                <i class='bx bx-plus text-xl'></i>
                <span>Agregar Salón</span>
            </button>
        </div>


        <div class="flex flex-col justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-700">FILTROS</h2>
            <div class="flex space-x-4">
                <!-- Filtro de Edificio -->
                <select id="filterEdificio" class="border rounded-lg px-4 py-2">
                    <option value="">Todos los Edificios</option>
                    @foreach ($edificios as $edificio)
                        <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                    @endforeach
                </select>

                <!-- Filtro de Piso -->
                <select id="filterPiso" class="border rounded-lg px-4 py-2" disabled>
                    <option value="">Todos los Pisos</option>
                </select>
            </div>

        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table id="tablaSalones" class="min-w-full divide-y divide-gray-200 rounded-lg border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Piso
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Edificio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($salones as $salon)
                            <tr data-edificio-id="{{ $salon->piso->edificio->id ?? '' }}"
                                data-piso-id="{{ $salon->idPiso }}">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $salon->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $salon->piso->numero ?? 'Sin piso' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $salon->piso->edificio->nombre ?? 'Sin edificio' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 flex space-x-2">
                                    <button
                                        onclick="abrirModalEditar('{{ $salon->idSalon }}', '{{ $salon->nombre }}', '{{ $salon->piso->idEdificio }}', '{{ $salon->idPiso }}')"
                                        class="text-blue-500 hover:text-blue-600">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>
                                    <button onclick="mostrarQR('{{ $salon->idSalon }}', '{{ $salon->nombre }}')" class="text-gray-500 hover:text-gray-600">
                                        <i class='bx bx-qr text-xl'></i>
                                    </button>

                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal Agregar -->
<div id="modalAgregar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div
        class="bg-white w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0 modal-content">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start">
                <div class="flex gap-2">
                    <i class='bx bx-door-open text-3xl text-green-500'></i>
                    <h2 id="modalTitulo" class="text-xl font-bold">Agregar Salón</h2>
                </div>
                <p class="text-sm text-slate-500 mt-2">Ingrese los detalles del nuevo salón aquí. Haga clic en guardar
                    cuando termine.</p>
            </div>
            <button onclick="cerrarModalAgregar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formAgregarSalon" method="POST" action="{{ route('salones.store') }}">
            @csrf
            <div class="mb-4">
                <label for="nombreAgregar" class="block text-gray-700">Nombre</label>
                <input type="text" id="nombreAgregar" name="nombre"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="50" oninput="validarNombre(this)" placeholder="Ejemplo: F12 Aula de Cómputo">
            </div>
            <div class="mb-4">
                <label for="edificioAgregar" class="block text-gray-700">Edificio</label>
                <select id="edificioAgregar" name="idEdificio" class="w-full px-4 py-2 border rounded-lg" required
                    onchange="cargarPisosAgregar(this.value)">
                    <option value="">Seleccione un edificio</option>
                    @foreach ($edificios as $edificio)
                        <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="pisoAgregar" class="block text-gray-700">Piso</label>
                <select id="pisoAgregar" name="idPiso" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Seleccione un piso</option>
                </select>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Guardar</button>
                <button type="button" onclick="cerrarModalAgregar()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div
        class="bg-white w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl rounded-lg shadow-lg p-6 relative transition-transform transform scale-90 opacity-0 modal-content">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start">
                <div class="flex gap-2">
                    <i class='bx bx-door-open text-3xl text-blue-500'></i>
                    <h2 class="text-xl font-bold">Editar Salón</h2>
                </div>
                <p class="text-sm text-slate-500 mt-2">Ingrese los detalles a cambiar del salón aquí. Haga clic en
                    guardar cuando termine.</p>
            </div>
            <button onclick="cerrarModalEditar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEditarSalon" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label for="nombreEditar" class="block text-gray-700">Nombre</label>
                <input type="text" id="nombreEditar" name="nombre" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required maxlength="50" oninput="validarNombre(this)">
            </div>
            <div class="mb-4">
                <label for="edificioEditar" class="block text-gray-700">Edificio</label>
                <select id="edificioEditar" name="idEdificio" class="w-full px-4 py-2 border rounded-lg" required
                    onchange="cargarPisosEditar(this.value)">
                    <option value="">Seleccione un edificio</option>
                    @foreach ($edificios as $edificio)
                        <option value="{{ $edificio->id }}">{{ $edificio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="pisoEditar" class="block text-gray-700">Piso</label>
                <select id="pisoEditar" name="idPiso" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">Seleccione un piso</option>
                </select>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Guardar</button>
                <button type="button" onclick="cerrarModalEditar()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Cancelar</button>
            </div>
        </form>
    </div>
</div>

    <script>
        function abrirModalAgregar() {
            const modal = document.getElementById('modalAgregar');
            modal.classList.remove('hidden');
            modal.querySelector('.modal-content').classList.remove('scale-90', 'opacity-0');
        }

        function cerrarModalAgregar() {
            const modal = document.getElementById('modalAgregar');
            modal.classList.add('hidden');
            modal.querySelector('.modal-content').classList.add('scale-90', 'opacity-0');
        }

        function abrirModalEditar(id, nombre, idEdificio, idPiso) {
            const modal = document.getElementById('modalEditar');
            modal.classList.remove('hidden');
            modal.querySelector('.modal-content').classList.remove('scale-90', 'opacity-0');

            const form = document.getElementById('formEditarSalon');
            form.action = `/salones/${id}`;

            document.getElementById('nombreEditar').value = nombre;
            document.getElementById('edificioEditar').value = idEdificio;

            cargarPisosEditar(idEdificio, idPiso);
        }

        function cerrarModalEditar() {
            const modal = document.getElementById('modalEditar');
            modal.classList.add('hidden');
            modal.querySelector('.modal-content').classList.add('scale-90', 'opacity-0');
        }

        function cargarPisosAgregar(idEdificio) {
            const pisoSelect = document.getElementById('pisoAgregar');
            pisoSelect.innerHTML = '<option value="">Seleccione un piso</option>';
            if (idEdificio) {
                fetch(`/pisos/por-edificio/${idEdificio}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(piso => {
                            const option = document.createElement('option');
                            option.value = piso.id;
                            option.textContent = piso.numero;
                            pisoSelect.appendChild(option);
                        });
                    });
            }
        }

        function cargarPisosEditar(idEdificio, selectedPiso = null) {
            const pisoSelect = document.getElementById('pisoEditar');
            pisoSelect.innerHTML = '<option value="">Seleccione un piso</option>';
            if (idEdificio) {
                fetch(`/pisos/por-edificio/${idEdificio}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(piso => {
                            const option = document.createElement('option');
                            option.value = piso.id;
                            option.textContent = piso.numero;
                            if (piso.id == selectedPiso) option.selected = true;
                            pisoSelect.appendChild(option);
                        });
                    });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#tablaSalones').DataTable({
                responsive: true,
                dom: '<"flex justify-between items-center mb-4"lf>tip',
                language: {
                    search: "",
                    searchPlaceholder: "Buscar salón...",
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

            const filterEdificio = document.getElementById('filterEdificio');
            const filterPiso = document.getElementById('filterPiso');

            // Filtrar por edificio
            filterEdificio.addEventListener('change', function() {
                const edificioId = this.value;

                // Resetear filtro de pisos si cambia el edificio
                filterPiso.innerHTML = '<option value="">Todos los Pisos</option>';
                filterPiso.disabled = true;

                // Si hay un edificio seleccionado, cargar los pisos
                if (edificioId) {
                    cargarPisosFiltro(edificioId);
                    filterPiso.disabled = false;

                    // Aplicar filtro por edificio en la tabla
                    table.rows().every(function() {
                        const row = $(this.node());
                        row.toggle(row.data('edificio-id') == edificioId);
                    });
                } else {
                    // Mostrar todos si no hay filtro
                    table.rows().every(function() {
                        $(this.node()).show();
                    });
                }
            });

            // Filtrar por piso
            filterPiso.addEventListener('change', function() {
                const pisoId = this.value;

                if (pisoId) {
                    table.rows().every(function() {
                        const row = $(this.node());
                        row.toggle(row.data('piso-id') == pisoId);
                    });
                } else {
                    // Mostrar todos si no hay filtro de pisos
                    table.rows().every(function() {
                        $(this.node()).show();
                    });
                }
            });

            // Función para cargar los pisos en el filtro
            function cargarPisosFiltro(edificioId) {
                fetch(`/pisos/por-edificio/${edificioId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(piso => {
                            const option = document.createElement('option');
                            option.value = piso.id;
                            option.textContent = piso.numero;
                            filterPiso.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al cargar los pisos:', error));
            }
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

        let isComposing = false;

        function validateNameInput(event) {
            if (isComposing) return;

            const input = event.target;

            // Solo permite letras, vocales acentuadas y espacios
            const validValue = input.value.normalize('NFC').replace(/[^a-zA-Z1-9\u00C0-\u017FñÑ\s]/g, '');

        }
        document.addEventListener("DOMContentLoaded", () => {
            const nameInputs = document.querySelectorAll("#nombreAgregar, #nombreEditar");

            nameInputs.forEach((input) => {
                // Detectar caracteres combinados (e.g., `´` seguido de vocal)
                input.addEventListener("compositionstart", () => {
                    isComposing = true;
                });

                input.addEventListener("compositionend", () => {
                    isComposing = false;
                    validateNameInput({
                        target: input
                    });
                });

                // Validar en tiempo real mientras se escribe
                input.addEventListener("input", validateNameInput);
            });
        });
        function mostrarQR(idSalon, nombreSalon) {
    const qrData = `${idSalon}/${encodeURIComponent(nombreSalon)}`;
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrData)}&size=200x200`;
    
    Swal.fire({
        title: 'Código QR del Salón',
        text: `Código QR para el salón: ${nombreSalon}`,
        imageUrl: qrUrl,
        imageWidth: 200,
        imageHeight: 200,
        imageAlt: 'Código QR',
    });
}



    function cerrarModalQR() {
        const modal = document.getElementById('modalQR');
        modal.classList.add('hidden');
        modal.querySelector('.modal-content').classList.add('scale-90', 'opacity-0');
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>


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
