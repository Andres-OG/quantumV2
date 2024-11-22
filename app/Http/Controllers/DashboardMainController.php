<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardMainController extends Controller
{
    public function index()
    {
        return view('main.DashboardMain');
    }

    public function getDashboardData()
    {
        try {
            // Datos reales de usuarios
            $totalUsers = User::count();
            $lastMonthUsers = User::where('created_at', '>=', now()->subMonth())->count();
            $growthPercentageUsers = $lastMonthUsers > 0 ? round(($lastMonthUsers / max(1, $totalUsers)) * 100, 2) : 0;
            $activeUsers = User::where('status', 1)->count();
            $inactiveUsers = User::where('status', 0)->count();

            // Datos para el gráfico de crecimiento
            $userGrowthData = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('count', 'date');

            return response()->json([
                'totalUsers' => $totalUsers,
                'lastMonthUsers' => $lastMonthUsers,
                'growthPercentageUsers' => $growthPercentageUsers,
                'activeUsers' => $activeUsers,
                'inactiveUsers' => $inactiveUsers,
                'userGrowthData' => $userGrowthData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener los datos del dashboard: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al cargar los datos del dashboard.'], 500);
        }
    }
}
