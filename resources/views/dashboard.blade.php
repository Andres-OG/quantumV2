@extends('layout')

@section('title', 'Estadísticas')

@section('content')

<div class="mb-6">
    <h2 class="text-3xl font-bold">Panel de Administrador</h2>
    <h3 class="text-xl font-light">Resumen de las métricas clave del sistema</h3>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Instituciones -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Total Instituciones</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalInstitutions }}</p>
            <p class="text-sm text-gray-500">+{{ $growthPercentageInstitutions }}% desde el último mes</p>
        </div>
        <i class="bx bx-buildings text-3xl text-gray-400"></i>
    </div>

    <!-- Instituciones Activas -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Instituciones Activas</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $activeInstitutionsCount }}</p>
            <p class="text-sm text-gray-500">{{ round(($activeInstitutionsCount / $totalInstitutions) * 100, 2) }}% del total</p>
        </div>
        <i class="bx bx-check-circle text-3xl text-green-400"></i>
    </div>

    <!-- Usuarios Registrados -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Usuarios Registrados</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            <p class="text-sm text-gray-500">+{{ $growthPercentageUsers }}% desde el último mes</p>
        </div>
        <i class="bx bx-user text-3xl text-blue-400"></i>
    </div>

    <!-- Crecimiento de Usuarios -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Crecimiento de Usuarios</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $userGrowthRate }}</p>
            <p class="text-sm text-gray-500">Nuevos este mes</p>
        </div>
        <i class="bx bx-trending-up text-3xl text-purple-400"></i>
    </div>
</div>

<!-- Charts -->
<div class="flex flex-col lg:flex-row gap-6 w-full">
    <div class="bg-white p-6 rounded-lg shadow w-full lg:w-1/3">
        <h3 class="text-lg font-medium mb-4">Distribución de Instituciones</h3>
        <canvas id="institutionChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow w-full lg:w-2/3">
        <h3 class="text-lg font-medium mb-4">Crecimiento de Usuarios</h3>
        <canvas id="userGrowthChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/dashboard-data')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                const institutionsData = data.institutionsDistribution || {};
                const userGrowthData = data.userGrowthData || {};

                // Crear la gráfica de instituciones
                if (Object.keys(institutionsData).length > 0) {
                    const institutionCtx = document.getElementById('institutionChart').getContext('2d');
                    new Chart(institutionCtx, {
                        type: 'doughnut',
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                            },
                        },
                        data: {
                            labels: Object.keys(institutionsData).map(status =>
                                status == 1 ? 'Activo' : 'Inactivo'
                            ),
                            datasets: [{
                                data: Object.values(institutionsData),
                                backgroundColor: ['#4CAF50', '#F44336'],
                            }]
                        }
                    });
                }

                // Crear la gráfica de crecimiento de usuarios
                if (Object.keys(userGrowthData).length > 0) {
                    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
                    new Chart(userGrowthCtx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(userGrowthData),
                            datasets: [{
                                label: 'Crecimiento de Usuarios',
                                data: Object.values(userGrowthData),
                                borderColor: '#4CAF50',
                                fill: false,
                            }]
                        }
                    });
                }
            })
            .catch(error => console.error('Error al obtener los datos del dashboard:', error));
    });
</script>
@endsection
