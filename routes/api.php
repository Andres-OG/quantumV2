<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SuperAdminDashboardController;

Route::middleware('api')->group(function () {
    Route::post('/users/register', [UserController::class, 'store']);  
    Route::post('/mobile/login', [UserController::class, 'login'])->name('login');
    Route::post('/mobile/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
    Route::get('/horarios/maestro/{maestroId}/dia/{dia}', [HorarioController::class, 'horariosDelMaestroPorDia'])->name('horarios.horariosDelMaestroPorDia');
    
    Route::get('/horarios/salon/{salonId}/momento', [HorarioController::class, 'horarioEnElMomentoDelSalon'])->name('horarios.horarioEnElMomentoDelSalon');
      
    Route::get('/horarios/salon/{salonId}/dia/{dia}', [HorarioController::class, 'horarioDelSalonPorDia'])->name('horarios.horarioDelSalonPorDia');
    Route::get('/horarios/salon/{salonId}/semana', [HorarioController::class, 'horarioDelSalonPorSemana'])->name('horarios.horarioDelSalonPorSemana');
    Route::get('/horarios/disponibles/hoy', [HorarioController::class, 'todosLosHorariosDisponiblesHoy'])->name('horarios.todosLosHorariosDisponiblesHoy');

    // Rutas de Ricardo
    Route::get('/institutions/{id}/colors-and-name', [SuperAdminDashboardController::class, 'getInstitutionColorsAndName']);
    Route::get('/materias/dia/{dia}', [HorarioController::class, 'materiasPorDia'])->name('materias.porDia');
    Route::get('/materias/semana', [HorarioController::class, 'materiasPorSemana'])->name('materias.porSemana');
    Route::get('/carreras/dia/{dia}', [HorarioController::class, 'carrerasPorDia'])->name('carreras.porDia');
    Route::get('/carreras/semana', [HorarioController::class, 'carrerasPorSemana'])->name('carreras.porSemana');
    Route::get('/eventos/dia/{dia}', [EventoController::class, 'eventosPorDia'])->name('eventos.porDia');
    Route::get('/eventos/semana/{startDate}/{endDate}', [EventoController::class, 'eventosPorSemana'])->name('eventos.porSemana');
    Route::get('/eventos/dia/{dia}', [EventoController::class, 'eventosPorDia'])->name('eventos.porDia');
    // Salones disponibles en un momento específico
    Route::get('/salones/disponibles/momento', [HorarioController::class, 'salonesDisponiblesMomento'])->name('salones.disponiblesMomento');
    Route::get('/salones/disponibles/dia/{dia}', [HorarioController::class, 'salonesDisponiblesDia'])->name('salones.disponiblesDia');
    Route::put('/users/{id_user}/update', [UserController::class, 'updateUser'])->name('user.updateUser');
});
