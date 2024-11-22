@extends('main.layoutMain')

@section('title', 'Gestión de Eventos')

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
        <h1 class="text-2xl font-bold">Gestión de Eventos</h1>
        <p class="mb-4">Administra los eventos de tu institución. Puedes agregar, editar y gestionar los eventos.</p>
    </div>
    <button onclick="abrirModalAgregar()" class="bg-green-500 text-white px-4 h-10 rounded hover:bg-green-600 sm:self-start flex items-center gap-2">
        <i class='bx bx-plus text-xl'></i>
        <span>Agregar Evento</span>
    </button>

</div>

<div class="flex flex-col sm:flex-row sm:justify-between items-start mb-4">
    <div class="mb-4 sm:mb-0">
        <h2 class="text-xl font-bold text-gray-700">FILTROS</h2>
        <p class="text-sm text-gray-500 mb-4">Filtra los eventos por salón y fecha.</p>
        <!-- Filtro por salón -->
        <select id="filtroSalon" class="border px-4 py-2 rounded mb-4 sm:mb-0">
            <option value="">Todos los salones</option>
            @foreach ($salones as $salon)
            <option value="{{ $salon->nombre }}">{{ $salon->nombre }}</option>
            @endforeach
        </select>

        <!-- Filtro por fechas -->
        <select id="filtroFecha" class="border px-4 py-2 rounded">
            <option value="">Todas las fechas</option>
            <option value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">Hoy</option>
            <option value="semana">Esta Semana</option>
            <option value="mes">Este Mes</option>
        </select>
    </div>
</div>

    <!-- Tabla de Eventos -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table id="tablaEventos" class="min-w-full divide-y divide-gray-200 rounded-lg">
            <thead class="bg-gray-100 rounded-t-lg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                        Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Hora
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salón
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($eventos as $evento)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evento->nombre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($evento->dia)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $evento->hora_inicio }} -
                        {{ $evento->hora_fin }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $evento->salon->nombre ?? 'Sin salón' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex gap-2">
                        <button onclick="cargarEvento(`{{ $evento->id }}`)"
                            class="text-blue-500 bg-transparent rounded p-1 hover:text-blue-600 hover:bg-blue-100 text-xl">
                            <i class='bx bx-edit'></i>
                        </button>
                        <form method="POST" action="{{ route('eventos.destroy', $evento->id) }}"
                            class="inline-block">
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
    <div class="bg-white w-full sm:max-w-md lg:max-w-lg xl:max-w-xl rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start space-x-2">
                <div class="flex gap-2">
                    <i class='bx bx-calendar-event text-3xl text-green-500'></i>
                    <h2 id="modalTituloAgregar" class="text-xl font-bold text-gray-700">Agregar Evento</h2>
                </div>
                <p class="text-sm text-slate-500">Ingrese los detalles del nuevo Evento aquí. Haga clic en guardar
                    cuando termine.</p>
            </div>
            <button onclick="cerrarModalAgregar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formAgregarEvento" method="POST" action="{{ route('eventos.store') }}">
            @csrf
            <div class="mb-4">
                <label for="nombreAgregar" class="block text-gray-700 font-bold">Nombre del Evento:</label>
                <input type="text" id="nombreAgregar" name="nombre" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="fechaAgregar" class="block text-gray-700 font-bold">Fecha:</label>
                <input type="date" id="fechaAgregar" name="dia" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="horaInicioAgregar" class="block text-gray-700 font-bold">Hora de Inicio:</label>
                <input type="time" id="horaInicioAgregar" name="hora_inicio" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="horaFinAgregar" class="block text-gray-700 font-bold">Hora de Fin:</label>
                <input type="time" id="horaFinAgregar" name="hora_fin" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="salonAgregar" class="block text-gray-700 font-bold">Salón:</label>
                <select id="salonAgregar" name="idSalon" class="w-full px-4 py-2 border rounded" required>
                    <option value="">Seleccione un salón</option>
                    @foreach ($salones as $salon)
                    <option value="{{ $salon->idSalon }}">{{ $salon->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
                <button type="button" onclick="cerrarModalAgregar()"
                    class="bg-gray-500 text-white px-4 py-2 ml-2 rounded hover:bg-gray-600">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white w-full sm:max-w-md lg:max-w-lg xl:max-w-xl rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-start space-x-2">
                <div class="flex gap-2">
                    <i class='bx bx-group text-3xl text-blue-500'></i>
                    <h2 id="modalTituloAgregar" class="text-xl font-bold text-gray-700">Editar Evento</h2>
                </div>
                <p class="text-sm text-slate-500">Modifique los detalles del evento aquí. Haga clic en guardar
                    cuando termine.</p>
            </div>
            <button onclick="cerrarModalEditar()" class="text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formEditarEvento" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nombreEditar" class="block text-gray-700 font-bold">Nombre del Evento:</label>
                <input type="text" id="nombreEditar" name="nombre" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="fechaEditar" class="block text-gray-700 font-bold">Fecha:</label>
                <input type="date" id="fechaEditar" name="dia" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="horaInicioEditar" class="block text-gray-700 font-bold">Hora de Inicio:</label>
                <input type="time" id="horaInicioEditar" name="hora_inicio"
                    class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="horaFinEditar" class="block text-gray-700 font-bold">Hora de Fin:</label>
                <input type="time" id="horaFinEditar" name="hora_fin" class="w-full px-4 py-2 border rounded"
                    required>
            </div>
            <div class="mb-4">
                <label for="salonEditar" class="block text-gray-700 font-bold">Salón:</label>
                <select id="salonEditar" name="idSalon" class="w-full px-4 py-2 border rounded" required>
                    <option value="">Seleccione un salón</option>
                    @foreach ($salones as $salon)
                    <option value="{{ $salon->idSalon }}">{{ $salon->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
                <button type="button" onclick="cerrarModalEditar()"
                    class="bg-gray-500 text-white px-4 py-2 ml-2 rounded hover:bg-gray-600">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        const tabla = $('#tablaEventos').DataTable({
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Buscar evento...",
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

        // Filtro por salón
        $('#filtroSalon').on('change', function() {
            const valor = $(this).val();
            tabla.column(3).search(valor).draw();
        });


        $('#filtroFecha').on('change', function() {
            const filtro = $(this).val();
            const today = new Date();
            const tabla = $('#tablaEventos').DataTable();

            tabla.rows().every(function() {
                const rowData = this.data();
                const fechaTexto = rowData[1]; // Columna de fecha en formato DD/MM/YYYY
                const [dia, mes, anio] = fechaTexto.split(
                    '/'); // Divide la fecha en día, mes y año
                const fechaEvento = new Date(
                    `${anio}-${mes}-${dia}`); // Convierte a formato ISO

                if (filtro === '') {
                    // Mostrar todos los registros
                    this.node().style.display = '';
                } else if (filtro === 'semana') {
                    // Filtrar eventos de esta semana
                    const startOfWeek = new Date(today.setDate(today.getDate() - today
                        .getDay() + 1)); // Lunes
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(endOfWeek.getDate() + 6); // Domingo

                    if (fechaEvento >= startOfWeek && fechaEvento <= endOfWeek) {
                        this.node().style.display = '';
                    } else {
                        this.node().style.display = 'none';
                    }
                } else if (filtro === 'mes') {
                    // Filtrar eventos de este mes
                    if (
                        fechaEvento.getMonth() === today.getMonth() &&
                        fechaEvento.getFullYear() === today.getFullYear()
                    ) {
                        this.node().style.display = '';
                    } else {
                        this.node().style.display = 'none';
                    }
                } else {
                    // Filtrar por día específico
                    const filtroFecha = new Date(filtro);
                    if (
                        fechaEvento.toDateString() === filtroFecha.toDateString()
                    ) {
                        this.node().style.display = '';
                    } else {
                        this.node().style.display = 'none';
                    }
                }
            });

            tabla.draw(false);
        });
    });

    function abrirModalAgregar() {
        document.getElementById('modalAgregar').classList.remove('hidden');
    }

    function cerrarModalAgregar() {
        document.getElementById('modalAgregar').classList.add('hidden');
    }

    function abrirModalAgregar() {
        document.getElementById('modalAgregar').classList.remove('hidden');
    }

    function cerrarModalAgregar() {
        document.getElementById('modalAgregar').classList.add('hidden');
    }

    function cargarEvento(id) {
        fetch(`/eventos-show/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del evento');
                }
                return response.json();
            })
            .then(evento => abrirModalEditar(evento))
            .catch(error => console.error(error));
    }

    function abrirModalEditar(evento) {
        const form = document.getElementById('formEditarEvento');
        form.action = `/eventos/${evento.id}`; // Asegúrate de que la ruta sea válida y accesible

        document.getElementById('nombreEditar').value = evento.nombre;
        document.getElementById('fechaEditar').value = evento.dia;
        document.getElementById('horaInicioEditar').value = evento.hora_inicio;
        document.getElementById('horaFinEditar').value = evento.hora_fin;
        document.getElementById('salonEditar').value = evento.salon.idSalon;

        document.getElementById('modalEditar').classList.remove('hidden');
    }


    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
    }

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
</script>
@endsection