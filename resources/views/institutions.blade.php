@extends('layout')

@section('title', 'Gestión de Instituciones')

@section('content')
<div class="mb-6 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl sm:text-3xl font-bold">Gestión de Instituciones</h2>
    <h3 class="text-lg sm:text-xl font-light">Administra las instituciones educativas registradas</h3>
</div>

<div class="bg-white p-4 sm:p-6 rounded shadow overflow-x-auto">
    <!-- Tabla para pantallas grandes -->
    <div class="hidden sm:block">
        <table id="institutionsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Administrador</th>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Correo Electrónico</th>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-2 sm:px-6 py-3 text-right text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($institutions as $institution)
                    @if ($institution->id_institution !== '1') {{-- Exclude institution with ID 1 --}}
                        <tr>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $institution->id_institution }}</td>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $institution->name }}</td>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $institution->admin->name ?? 'Sin asignar' }}</td>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $institution->admin->email ?? 'Sin asignar' }}</td>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                <form method="POST" action="{{ route('admin.institution.status.update', $institution->id_institution) }}" class="status-form">
                                    @csrf
                                    <select name="status" class="status-select border border-gray-300 rounded-lg py-1 px-2 focus:ring-gray-500 text-xs sm:text-sm">
                                        <option value="1" {{ $institution->status ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ !$institution->status ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                <form action="{{ route('admin.institution.delete', $institution->id_institution) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-500 hover:text-red-700 delete-button">
                                        <i class="bx bx-trash text-lg sm:text-xl"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Formato para pantallas pequeñas -->
    <div class="block sm:hidden grid grid-cols-1 gap-4">
        @foreach ($institutions as $institution)
            @if ($institution->id_institution !== '1')
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex justify-between">
                        <div><strong>ID:</strong> {{ $institution->id_institution }}</div>
                        <div><strong>Nombre:</strong> {{ $institution->name }}</div>
                    </div>
                    <div class="mt-2"><strong>Administrador:</strong> {{ $institution->admin->name ?? 'Sin asignar' }}</div>
                    <div><strong>Correo:</strong> {{ $institution->admin->email ?? 'Sin asignar' }}</div>
                    <div class="mt-2">
                        <form method="POST" action="{{ route('admin.institution.status.update', $institution->id_institution) }}" class="status-form">
                            @csrf
                            <select name="status" class="status-select border border-gray-300 rounded-lg py-1 px-2 focus:ring-gray-500 text-xs sm:text-sm">
                                <option value="1" {{ $institution->status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$institution->status ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </form>
                    </div>
                    <div class="mt-2 text-right">
                        <form action="{{ route('admin.institution.delete', $institution->id_institution) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-500 hover:text-red-700 delete-button">
                                <i class="bx bx-trash text-lg sm:text-xl"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm status change
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.addEventListener('change', function(event) {
                event.preventDefault();
                const form = this.closest('form');
                const newState = this.value === "1" ? "Activo" : "Inactivo";

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Cambiar el estado a "${newState}".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        // Revert selection if canceled
                        this.value = this.value === "1" ? "0" : "1";
                    }
                });
            });
        });

        // Confirm delete
        document.querySelectorAll('.delete-button').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const form = this.closest('form');

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
