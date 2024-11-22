@extends('main.layoutMain')

@section('title', 'Gestión de Horarios')

@section('content')
<div class="container  p-6">

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 alerta-temporal">
        <ul class="rounded-xl font-bold">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 alerta-temporal">
        {{ session('success') }}
    </div>
    @endif

    <div class="rounded-lg mb-6">
        <!-- Título y descripción -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Horarios</h1>
            <p class="text-gray-600">
                Administra los horarios de tu institución. Puedes agregar, editar y gestionar los horarios.
            </p>
        </div>

        <!-- Opciones de acciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
            <!-- Botón de agregar -->
            <button onclick="abrirModal('new')"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300 flex justify-center items-center gap-2">
                <i class='bx bx-plus text-lg'></i>
                <span>Agregar Horario</span>
            </button>

            <!-- Descargar plantilla -->
            <a href="{{ route('horarios.downloadTemplate') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 flex justify-center items-center gap-2">
                <i class='bx bx-download text-lg'></i>
                <span>Descargar Plantilla</span>
            </a>

            <!-- Subir Excel -->
            <form id="uploadForm" action="/horarios/uploadExcel" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                @csrf
                <label for="excelFile" class="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 flex justify-center items-center gap-2">
                    <i class="bx bx-upload text-lg"></i>
                    <span>Seleccionar Archivo</span>
                    <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" class="hidden" required>
                </label>
                <input type="text" id="fileName" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-500"
                    readonly placeholder="No se ha seleccionado ningún archivo">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">
                    Subir Excel
                </button>
            </form>

            <!-- Eliminar por periodo -->
            <form id="deleteByPeriodForm" method="POST" class="flex flex-col gap-2">
                @csrf
                @method('DELETE')
                <select id="periodo" name="periodo" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="" disabled selected>Selecciona un período</option>
                    @foreach ($periodos as $periodo)
                    <option value="{{ $periodo }}">{{ $periodo }}</option>
                    @endforeach
                </select>
                <button type="button" id="deleteButton"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">
                    <i class='bx bx-trash'></i>
                    Eliminar
                </button>
            </form>
        </div>
    </div>


</div>


<div class="p-6 mx-0">
    <h2 class="text-xl font-bold text-gray-700 mb-4">FILTROS</h2>
    <div class="flex flex-wrap gap-4 mb-4">
        <select id="filtroSalon" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded">
            <option value="">Todos los salones</option>
            @foreach ($salones as $salon)
            <option value="{{ $salon->nombre }}">{{ $salon->nombre }}</option>
            @endforeach
        </select>

        <select id="filtroPeriodo" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded" onchange="filtrarPorPeriodo()">
            <option value="">Todos los periodos</option>
            @foreach ($periodos as $periodo)
            <option value="{{ $periodo }}">{{ $periodo }}</option>
            @endforeach
        </select>

        <select id="filtroDia" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded">
            <option value="">Todos los días</option>
            <option value="lunes">Lunes</option>
            <option value="martes">Martes</option>
            <option value="miércoles">Miércoles</option>
            <option value="jueves">Jueves</option>
            <option value="viernes">Viernes</option>
            <option value="sábado">Sábado</option>
        </select>

        <select id="filtroMaestro" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded">
            <option value="">Todos los maestros</option>
            @foreach ($maestros as $maestro)
            <option value="{{ $maestro->nombre }}">{{ $maestro->nombre }}</option>
            @endforeach
        </select>

        <select id="filtroMateria" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded">
            <option value="">Todas las materias</option>
            @foreach ($grupos as $grupo)
            <option value="{{ $grupo->materia->nombre }}">{{ $grupo->materia->nombre }}</option>
            @endforeach
        </select>

        <select id="filtroGrupo" class="w-full sm:w-1/3 md:w-1/5 px-4 py-2 border rounded">
            <option value="">Todos los grupos</option>
            @foreach ($grupos as $grupo)
            <option value="{{ $grupo->nombre }}">{{ $grupo->nombre }}</option>
            @endforeach
        </select>
    </div>
</div>


<!-- Tabla Consolidada de Horarios -->
<div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
    <table id="tablaHorarios" class="min-w-full divide-y divide-gray-200 rounded-lg">
        <thead class="bg-gray-100 rounded-t-lg">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Día</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia-Grupo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maestro</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salón</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($horarios as $horario)
            <tr class="border-b">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($horario->dia) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horario->horaInicio }} - {{ $horario->horaFin }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horario->grupo->materia->nombre }} - {{ $horario->grupo->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horario->maestro->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horario->salon->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $horario->periodo }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex gap-2">
                    <button
                        onclick="cargarHorario('{{ $horario->idHorario }}')"
                        class="text-blue-500 bg-transparent rounded p-1 hover:text-blue-600 hover:bg-blue-100 text-xl">
                        <i class='bx bx-edit'></i>
                    </button>
                    <form action="{{ route('horarios.destroy', $horario->idHorario) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 bg-transparent rounded p-1 hover:text-red-600 hover:bg-red-100 text-xl"
                            onclick="return confirm('¿Está seguro de eliminar este horario?')">
                            <i class='bx bx-trash'></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal de Formulario -->
<div id="modalFormulario" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white w-full sm:w-11/12 md:w-1/3 lg:w-1/4 xl:w-1/4 rounded shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 id="modalTitulo" class="text-xl font-bold">Editar Horario</h2>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="formHorario" method="POST" action="#">
            @csrf
            <!-- El método PUT será añadido dinámicamente en JavaScript si es necesario -->
            <input type="hidden" id="idHorario" name="idHorario" value="">

            <!-- Campos del formulario -->
            <div class="mb-4">
                <label for="horaInicio" class="block text-gray-700 font-bold">Hora de inicio:</label>
                <input type="time" id="horaInicio" name="horaInicio" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="horaFin" class="block text-gray-700 font-bold">Hora de fin:</label>
                <input type="time" id="horaFin" name="horaFin" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="dia" class="block text-gray-700 font-bold">Día:</label>
                <select id="dia" name="dia" class="w-full px-4 py-2 border rounded" required>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miércoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sábado">Sábado</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="idGrupo" class="block text-gray-700 font-bold">Grupo:</label>
                <select id="idGrupo" name="idGrupo" class="w-full px-4 py-2 border rounded" required>
                    <option value="">Seleccione un grupo</option>
                    @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->idGrupo }}">{{ $grupo->nombre }} - {{ $grupo->materia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="idMaestro" class="block text-gray-700 font-bold">Maestro:</label>
                <select id="idMaestro" name="idMaestro" class="w-full px-4 py-2 border rounded" required>
                    <option value="">Seleccione un maestro</option>
                    @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}">{{ $maestro->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="idSalon" class="block text-gray-700 font-bold">Salón:</label>
                <select id="idSalon" name="idSalon" class="w-full px-4 py-2 border rounded" required>
                    <option value="">Seleccione un salón</option>
                    @foreach ($salones as $salon)
                    <option value="{{ $salon->idSalon }}">{{ $salon->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="periodo" class="block text-gray-700 font-bold">Periodo:</label>
                <input type="text" id="periodo" name="periodo" class="w-full px-4 py-2 border rounded" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar Cambios</button>
                <button type="button" onclick="cerrarModal()" class="bg-gray-500 text-white px-4 py-2 ml-2 rounded hover:bg-gray-600">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabla = $('#tablaHorarios').DataTable({
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: "Buscar...",
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior",
                },
            },
        });

        // Función para aplicar los filtros personalizados
        function aplicarFiltros() {
            const filtroSalon = $('#filtroSalon').val().toLowerCase();
            const filtroDia = $('#filtroDia').val().toLowerCase();
            const filtroMaestro = $('#filtroMaestro').val().toLowerCase();
            const filtroMateria = $('#filtroMateria').val().toLowerCase();
            const filtroGrupo = $('#filtroGrupo').val().toLowerCase();

            tabla.rows().every(function() {
                const data = this.data(); // Obtiene los datos de la fila

                const dia = data[0].toLowerCase(); // Día
                const hora = data[1].toLowerCase(); // Hora
                const materiaGrupo = data[2].toLowerCase(); // Materia-Grupo
                const maestro = data[3].toLowerCase(); // Maestro
                const salon = data[4].toLowerCase(); // Salón

                // Verifica si cada filtro coincide con los datos
                const cumpleSalon = !filtroSalon || salon.includes(filtroSalon);
                const cumpleDia = !filtroDia || dia.includes(filtroDia);
                const cumpleMaestro = !filtroMaestro || maestro.includes(filtroMaestro);
                const cumpleMateria = !filtroMateria || materiaGrupo.includes(filtroMateria);
                const cumpleGrupo = !filtroGrupo || materiaGrupo.includes(filtroGrupo);

                // Muestra u oculta la fila dependiendo de los filtros
                if (cumpleSalon && cumpleDia && cumpleMaestro && cumpleMateria && cumpleGrupo) {
                    $(this.node()).show();
                } else {
                    $(this.node()).hide();
                }
            });
        }

        // Asigna eventos a los filtros
        $('#filtroSalon, #filtroDia, #filtroMaestro, #filtroMateria, #filtroGrupo').on('change', aplicarFiltros);
    });



    function cargarHorario(id) {
        fetch(`/horarios/edit/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del horario');
                }
                return response.json();
            })
            .then(horario => abrirModal('edit', horario))
            .catch(error => console.error('Error:', error));
    }

    function abrirModal(type, horario = null) {
        const modal = document.getElementById('modalFormulario');
        const titulo = document.getElementById('modalTitulo');
        const form = document.getElementById('formHorario');
        const idHorario = document.getElementById('idHorario');
        const horaInicio = document.getElementById('horaInicio');
        const horaFin = document.getElementById('horaFin');
        const dia = document.getElementById('dia');
        const idGrupo = document.getElementById('idGrupo');
        const idMaestro = document.getElementById('idMaestro');
        const idSalon = document.getElementById('idSalon');
        const periodo = document.getElementById('periodo');

        // Limpiar el formulario
        form.reset();
        idHorario.value = '';

        if (type === 'edit' && horario) {
            // Configuración para editar
            titulo.innerText = 'Editar Horario';
            form.action = `/horarios/update/${horario.idHorario}`;
            form.method = 'POST';

            // Asigna los valores al formulario
            idHorario.value = horario.idHorario;
            console.log(horario.horaInicio); // Esto debería mostrar algo como "14:30"
            horaInicio.value = horario.horaInicio;

            horaFin.value = horario.horaFin; // También en formato "HH:mm"
            dia.value = horario.dia;
            idGrupo.value = horario.idGrupo;
            idMaestro.value = horario.idMaestro;
            idSalon.value = horario.idSalon;
            periodo.value = horario.periodo;

            // Añadir método PUT
            if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }
        } else {
            // Configuración para agregar
            titulo.innerText = 'Agregar Horario';
            form.action = '/horarios';
            form.method = 'POST';

            // Eliminar método PUT si existe
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        }

        modal.classList.remove('hidden');
    }


    function cerrarModal() {
        const modal = document.getElementById('modalFormulario');
        modal.classList.add('hidden');
    }

    function filtrarPorPeriodo() {
        const periodo = document.getElementById('filtroPeriodo').value;
        const tabla = document.getElementById('tablaHorarios').querySelector('tbody');
        const filas = tabla.querySelectorAll('tr');

        filas.forEach(fila => {
            const periodoFila = fila.cells[5].innerText.trim();
            if (periodo === '' || periodo === periodoFila) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    function eliminarPorPeriodo() {
        const periodo = document.getElementById('periodo').value;
        if (!periodo) {
            alert('Seleccione un periodo para eliminar.');
            return;
        }

        if (!confirm(`¿Está seguro de eliminar todos los horarios del periodo ${periodo}?`)) return;

        fetch(`/horarios/delete-by-period/${periodo}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else {
                    alert(data.error || 'Error al eliminar horarios.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Ocurrió un error al eliminar los horarios.');
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const excelFileInput = document.getElementById('excelFile');
        const fileNameDisplay = document.getElementById('fileName');

        // Escucha el evento "change" del input para archivos
        excelFileInput.addEventListener('change', function() {
            const file = excelFileInput.files[0]; // Obtén el archivo seleccionado
            fileNameDisplay.value = file ? file.name : 'No se ha seleccionado ningún archivo'; // Actualiza el campo de texto
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.getElementById('deleteButton');
        const periodoSelect = document.getElementById('periodo');
        const form = document.getElementById('deleteByPeriodForm');

        deleteButton.addEventListener('click', function() {
            const selectedPeriod = periodoSelect.value;

            if (!selectedPeriod) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona un período',
                    text: 'Debes seleccionar un período antes de eliminar.',
                });
                return;
            }

            Swal.fire({
                title: `¿Estás seguro de eliminar todos los horarios del período "${selectedPeriod}"?`,
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Configurar la acción del formulario con el período seleccionado
                    form.action = `/horarios/delete-by-period/${selectedPeriod}`;
                    form.submit(); // Enviar el formulario
                }
            });
        });
    });

    // Esconde automáticamente las alertas después de 3 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alertas = document.querySelectorAll('.alerta-temporal'); // Busca las alertas con esta clase

        alertas.forEach(alerta => {
            setTimeout(() => {
                alerta.classList.add('hidden'); // Oculta la alerta después de 3 segundos
            }, 3000); // 3000 ms = 3 segundos
        });
    });

    function procesarExcel() {
        const file = document.getElementById('excelFile').files[0];
        const formData = new FormData();
        formData.append('excelFile', file);

        fetch("{{ route('horarios.uploadExcel') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Ocurrió un error al procesar el archivo.");
            });
    }
</script>
@endsection