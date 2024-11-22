<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuperAdminDashboardController extends Controller
{
    public function showDashboard()
    {
        try {
            // Total de instituciones
            $totalInstitutions = Institution::count();

            // Instituciones activas e inactivas
            $activeInstitutionsCount = Institution::where('status', 1)->count();
            $inactiveInstitutionsCount = Institution::where('status', 0)->count();

            // Crecimiento de instituciones
            $lastMonthInstitutions = Institution::where('created_at', '>=', now()->subMonth())->count();
            $growthPercentageInstitutions = $lastMonthInstitutions > 0 ? round(($lastMonthInstitutions / max(1, $totalInstitutions)) * 100, 2) : 0;

            // Total de usuarios registrados
            $totalUsers = User::count();

            // Crecimiento de usuarios
            $lastMonthUsers = User::where('created_at', '>=', now()->subMonth())->count();
            $growthPercentageUsers = $lastMonthUsers > 0 ? round(($lastMonthUsers / max(1, $totalUsers)) * 100, 2) : 0;

            // Crecimiento de usuarios (valor exacto para KPI)
            $userGrowthRate = $lastMonthUsers;

            return view('dashboard', compact(
                'totalInstitutions',
                'activeInstitutionsCount',
                'growthPercentageInstitutions',
                'totalUsers',
                'growthPercentageUsers',
                'userGrowthRate'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar el dashboard: ' . $e->getMessage());
            return view('dashboard')->with('error', 'Hubo un error al cargar los datos.');
        }
    }

    public function showInstitutions()
    {
        try {
            $institutions = Institution::where('id_institution', '!=', '5e4c90d0-1562-43d0-b00f-65c4fb24e563')->get();
            return view('institutions', compact('institutions'));
        } catch (\Exception $e) {
            Log::error('Error al cargar las instituciones: ' . $e->getMessage());
            return view('institutions')->with('error', 'Hubo un error al cargar las instituciones.');
        }
    }

    public function deleteInstitution($id)
    {
        try {
            $institution = Institution::findOrFail($id);
            $institution->delete();

            return redirect()->back()->with('status', 'Institución eliminada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la institución: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al intentar eliminar la institución.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $status = $request->input('status');
            $institution = Institution::findOrFail($id);

            if ($institution->status != $status) {
                $institution->status = $status;
                $institution->save();

                $message = $status ? 'La institución está ahora activa.' : 'La institución está ahora inactiva.';
                return redirect()->back()->with('status', $message);
            }

            return redirect()->back()->with('status', 'No se realizaron cambios en el estado.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el estado: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al intentar actualizar el estado.');
        }
    }

    // API para enviar datos a las gradocas
    public function getDashboardData()
    {
        try {
            $institutionsDistribution = Institution::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $userGrowthData = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('count', 'date');

            return response()->json([
                'institutionsDistribution' => $institutionsDistribution,
                'userGrowthData' => $userGrowthData
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los datos del dashboard.'], 500);
        }
    }

    public function getInstitutionColorsAndName($id)
    {
        try {
            // Obtener la institución por su ID
            $institution = Institution::select('name', 'colorP', 'colorS')
                ->where('id_institution', $id)
                ->first();

            if (!$institution) {
                return response()->json([
                    'message' => 'Institución no encontrada.',
                ], 404);
            }

            return response()->json([
                'data' => $institution,
                'message' => 'Colores y nombre de la institución obtenidos correctamente.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener colores y nombre de la institución: ' . $e->getMessage());

            return response()->json([
                'error' => 'Hubo un error al obtener los datos de la institución.',
            ], 500);
        }
    }
}
