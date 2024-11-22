@extends('main.layoutMain')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold">Panel de administración de institución</h2>
    <h3 class="text-xl font-light">Resumen de las métricas clave de la institución</h3>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Maestros -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Total Maestros</h3>
            <p id="totalMaestros" class="text-3xl font-bold text-gray-900">Cargando...</p>
        </div>
        <i class="bx bx-user text-3xl text-blue-400"></i>
    </div>

    <!-- Nuevos Maestros -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Nuevos Maestros</h3>
            <p id="nuevosMaestrosMes" class="text-3xl font-bold text-gray-900">Cargando...</p>
            <p id="crecimientoMaestros" class="text-sm text-green-600">Cargando...</p>
        </div>
        <i class="bx bx-trending-up text-3xl text-purple-400"></i>
    </div>

    <!-- Total Horarios -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Total Horarios</h3>
            <p id="totalHorarios" class="text-3xl font-bold text-gray-900">Cargando...</p>
        </div>
        <i class="bx bx-calendar text-3xl text-green-400"></i>
    </div>

    <!-- Horarios del Día -->
    <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-500">Horarios del Día</h3>
            <p id="todayHorarios" class="text-3xl font-bold text-gray-900">Cargando...</p>
        </div>
        <i class="bx bx-time text-3xl text-orange-400"></i>
    </div>
</div>

<!-- Gráficos -->
<div class="flex flex-col sm:flex-row gap-6 w-full">
    <!-- Crecimiento de Usuarios -->
    <div class="bg-white p-6 rounded-lg shadow sm:w-1/2 lg:w-1/3">
        <h3 class="text-lg font-medium mb-4">Crecimiento de Usuarios</h3>
        <canvas id="userGrowthChart"></canvas>
    </div>

    <!-- Maestros por Carrera -->
    <div class="bg-white p-6 rounded-lg shadow sm:w-1/2 lg:w-1/3">
        <h3 class="text-lg font-medium mb-4">Maestros por Carrera</h3>
        <canvas id="maestrosPorCarreraChart"></canvas>
    </div>

    <!-- Horarios por Día -->
    <div class="bg-white p-6 rounded-lg shadow sm:w-1/2 lg:w-1/3">
        <h3 class="text-lg font-medium mb-4">Horarios por Día</h3>
        <canvas id="horariosPorDiaChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const userResponse = await fetch("{{ route('api.institution.dashboard.data') }}");
            const userData = await userResponse.json();

            console.log(userData);

            const maestroResponse = await fetch("{{ route('api.maestros.dashboard') }}");
            const maestroData = await maestroResponse.json();

            console.log(maestroData);

            const horarioResponse = await fetch("{{ route('api.horarios.dashboard.data') }}");
            const horarioData = await horarioResponse.json();

            console.log(horarioData);

            if (userData.error || maestroData.error || horarioData.error) {
                console.error(userData.error || maestroData.error || horarioData.error);
                return;
            }

            // Usuarios KPIs
            if (document.getElementById('totalUsers')) {
                document.getElementById('totalUsers').textContent = userData.totalUsers || 0;
            }
            if (document.getElementById('lastMonthUsers')) {
                document.getElementById('lastMonthUsers').textContent = userData.lastMonthUsers || 0;
            }
            if (document.getElementById('growthPercentageUsers')) {
                document.getElementById('growthPercentageUsers').textContent = `+${userData.growthPercentageUsers || 0}% en el último mes`;
            }
            if (document.getElementById('activeUsers')) {
                document.getElementById('activeUsers').textContent = userData.activeUsers || 0;
            }
            if (document.getElementById('inactiveUsers')) {
                document.getElementById('inactiveUsers').textContent = userData.inactiveUsers || 0;
            }

            // Maestros KPIs
            if (document.getElementById('totalMaestros')) {
                document.getElementById('totalMaestros').textContent = maestroData.totalMaestros || 0;
            }
            if (document.getElementById('nuevosMaestrosMes')) {
                document.getElementById('nuevosMaestrosMes').textContent = maestroData.nuevosMaestrosMes || 0;
            }
            if (document.getElementById('crecimientoMaestros')) {
                document.getElementById('crecimientoMaestros').textContent = `+${maestroData.crecimientoMaestros || 0}% en el último mes`;
            }

            // Horarios KPIs
            if (document.getElementById('totalHorarios')) {
                document.getElementById('totalHorarios').textContent = horarioData.totalHorarios || 0;
            }
            if (document.getElementById('todayHorarios')) {
                document.getElementById('todayHorarios').textContent = horarioData.todayHorarios || 0;
            }

            // Crecimiento de Usuarios Chart
            if (userData.userGrowthData && Object.keys(userData.userGrowthData).length > 0) {
                const userCtx = document.getElementById('userGrowthChart').getContext('2d');
                new Chart(userCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(userData.userGrowthData),
                        datasets: [{
                            label: 'Crecimiento de Usuarios',
                            data: Object.values(userData.userGrowthData),
                            borderColor: '#4CAF50',
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        }
                    }
                });
            }

            // Maestros por Carrera Chart
            if (maestroData.maestrosPorCarrera && Object.keys(maestroData.maestrosPorCarrera).length > 0) {
                const maestroCtx = document.getElementById('maestrosPorCarreraChart').getContext('2d');
                new Chart(maestroCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(maestroData.maestrosPorCarrera),
                        datasets: [{
                            label: 'Maestros por Carrera',
                            data: Object.values(maestroData.maestrosPorCarrera),
                            backgroundColor: '#4CAF50',
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        }
                    }
                });
            }

            // Horarios por Día Chart
            if (horarioData.horariosPorDia && Object.keys(horarioData.horariosPorDia).length > 0) {
                const horarioCtx = document.getElementById('horariosPorDiaChart').getContext('2d');
                new Chart(horarioCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(horarioData.horariosPorDia),
                        datasets: [{
                            label: 'Horarios por Día',
                            data: Object.values(horarioData.horariosPorDia),
                            backgroundColor: '#FFCE56',
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error al cargar los datos del dashboard:', error);
        }
    });
</script>
@endsection
