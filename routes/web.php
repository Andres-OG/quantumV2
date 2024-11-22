<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterInstitutionController;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\DashboardMainController;

// Ruta para la página de inicio
Route::get('/', [HomeController::class, 'index'])->name('welcome');
// Rutas de inicio de sesión
Route::view('/login', 'login')->name('login'); // Vista del login

// Rutas de inicio de sesión
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/login', action: [LoginController::class, 'login'])->name('login.process'); // Procesar login


Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
});

// Rutas de registro
Route::get('/register', [RegisterInstitutionController::class, 'showInstitutionForm'])->name('register'); // Formulario de registro
Route::post('/register', [RegisterInstitutionController::class, 'storeInstitution'])->name('institution.store'); // Registrar institución
Route::get('/institutions', [RegisterInstitutionController::class, 'index'])->name('institutions.index');

// Rutas de pago
Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');
Route::get('/payment/failure', function () {
    return view('payment.failure');
})->name('payment.failure');

Route::middleware('auth')->group(function () {
    // Rutas del administrador
    Route::post('/admin/institution/{id}/status', [SuperAdminDashboardController::class, 'updateStatus'])->name('admin.institution.status.update');
    Route::delete('/admin/institution/{id}', [SuperAdminDashboardController::class, 'deleteInstitution'])->name('admin.institution.delete');
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.index');
    // Gestión de usuarios por el administrador
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle.status');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.user.delete');
    // Rutas del superadmin
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'showDashboard'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    
    // Rutas para carreras
    Route::get('/carreras', [CarreraController::class, 'index'])->name('carreras');
    // Ruta para crear una nueva carrera
    Route::get('/carreras/create', [CarreraController::class, 'create'])->name('carreras.create');
    // Ruta para almacenar la nueva carrera
    Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');
    // Ruta para editar una carrera existente
    Route::get('carreras/{id}/edit', [CarreraController::class, 'edit'])->name('carreras.edit');
    // Ruta para actualizar una carrera existente
    Route::put('/carreras/{id}', [CarreraController::class, 'update'])->name('carreras.update');
    // Ruta para obtener todas las carreras (JSON)
    Route::get('/carreras', [CarreraController::class, 'index'])->name('carreras.index');
    // Ruta para la vista principal
    Route::get('/carreras/vista', [CarreraController::class, 'vista'])->name('carreras.vista');

    Route::get('/carreras/gestion', [CarreraController::class, 'gestionCarreras'])->name('carreras.gestion');
});


Route::middleware('auth')->group(function () {
    //Rutas maestro 
    Route::get('/maestros', [MaestroController::class, 'index'])->name('maestros.index'); // Lista de maestros en JSON
    Route::get('/maestros/vista', [MaestroController::class, 'vista'])->name('maestros.vista'); // Vista de todos los maestros
    Route::get('/maestros/create', [MaestroController::class, 'create'])->name('maestros.create'); // Formulario de creación
    Route::post('/maestros', [MaestroController::class, 'store'])->name('maestros.store'); // Almacena un nuevo maestro
    Route::get('/maestros/{maestro}/edit', [MaestroController::class, 'edit'])->name('maestros.edit'); // Formulario de edición
    Route::put('/maestros/{maestro}', [MaestroController::class, 'update'])->name('maestros.update');
    // Ruta para eliminar un maestro
    Route::delete('/maestros/{maestro}', [MaestroController::class, 'destroy'])->name('maestros.destroy');
    //Route::get('/maestros/{maestro}', [MaestroController::class, 'show'])->name('maestros.show'); // Muestra un maestro específico en JSON

    Route::get('/maestros/gestion', [MaestroController::class, 'gestionMaestros'])->name('maestros.gestion');
});

Route::middleware('auth')->group(function () {

    Route::post('/edificios', [EdificioController::class, 'store'])->name('edificios.store');
    Route::put('/edificios/{edificio}', [EdificioController::class, 'update'])->name('edificios.update');
    Route::delete('/edificios/{edificio}', [EdificioController::class, 'destroy'])->name('edificios.destroy');
    Route::get('/edificios/gestion', [EdificioController::class, 'gestionEdificios'])->name('edificios.gestion');
});




Route::middleware('auth')->group(function () {
    // Rutas para eventos
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    // Ruta para mostrar la vista de eventos con salones
    Route::get('/eventos/vista', [EventoController::class, 'vista'])->name('eventos.vista');
    // Ruta para mostrar el formulario de edición de un evento específico
    Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    // Ruta para mostrar el formulario de creación de un evento
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    // Ruta para registrar un nuevo evento
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    // Ruta para obtener un evento específico por su ID
    //Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
    // Ruta para actualizar un evento específico por su ID
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');

    // Ruta para eliminar un evento específico por su ID
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::get('/eventos/gestion', [EventoController::class, 'gestion'])->name('eventos.gestion');
    // Ruta para obtener los eventos del día
    Route::get('/eventos/dia', [HorarioController::class, 'eventosDelDia'])->name('horarios.eventosDelDia');
});




Route::middleware('auth')->group(function () {
    // Rutas para grupos
    // Ruta para listar todos los grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    // Ruta para almacenar un nuevo grupo
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
    Route::get('/grupos/gestion', action: [GrupoController::class, 'gestionGrupos'])->name('grupos.gestion');
});



Route::middleware('auth')->group(function () {
    //horarios
    // Ruta para mostrar todos los horarios
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');

    // Ruta para mostrar la vista de los horarios
    Route::get('/horarios/vista', [HorarioController::class, 'vista'])->name('horarios.vista');

    // Ruta para crear un nuevo horario (formulario de creación)
    Route::get('/horarios/create', [HorarioController::class, 'create'])->name('horarios.create');

    // Ruta para almacenar un nuevo horario
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');

    // Ruta para editar un horario específico
    Route::get('/horarios/edit/{id}', [HorarioController::class, 'edit'])->name('horarios.edit');

    // Ruta para actualizar un horario
    Route::put('/horarios/update/{id}', [HorarioController::class, 'update'])->name('horarios.update');
    // Ruta para eliminar un horario
    Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

    // Ruta para eliminar horarios por periodo
    Route::delete('/horarios/delete-by-period/{periodo}', [HorarioController::class, 'destroyByPeriodo'])->name('horarios.deleteByPeriod');

    // Ruta para obtener los horarios de un maestro por día
    Route::get('/horarios/maestro/{maestroId}/dia/{dia}', [HorarioController::class, 'horariosDelMaestroPorDia'])->name('horarios.horariosDelMaestroPorDia');

    // Ruta para obtener el horario actual de un salón
    Route::get('/horarios/salon/{salonId}/momento', [HorarioController::class, 'horarioEnElMomentoDelSalon'])->name('horarios.horarioEnElMomentoDelSalon');

    // Ruta para obtener los horarios de un salón por día
    Route::get('/horarios/salon/{salonId}/dia/{dia}', [HorarioController::class, 'horarioDelSalonPorDia'])->name('horarios.horarioDelSalonPorDia');

    // Ruta para obtener los horarios de un salón por semana
    Route::get('/horarios/salon/{salonId}/semana', [HorarioController::class, 'horarioDelSalonPorSemana'])->name('horarios.horarioDelSalonPorSemana');

    // Ruta para obtener todos los horarios disponibles hoy
    Route::get('/horarios/disponibles/hoy', [HorarioController::class, 'todosLosHorariosDisponiblesHoy'])->name('horarios.todosLosHorariosDisponiblesHoy');

    Route::post('/horarios/uploadExcel', [HorarioController::class, 'uploadExcel'])->name('horarios.uploadExcel');
    Route::get('/horarios/download-template', [HorarioController::class, 'downloadTemplate'])->name('horarios.downloadTemplate');
    Route::get('/horarios/gestion', [HorarioController::class, 'gestionHorarios'])->name('horarios.gestion');
});


Route::middleware('auth')->group(function () {
    // Rutas para materias
    Route::get('/materias/create', [MateriaController::class, 'create'])->name('materias.create');
    Route::get('/materias', [MateriaController::class, 'index'])->name('materias.index');
    Route::get('/materias/vista', [MateriaController::class, 'vista'])->name('materias.vista');
    //Route::get('/materias/{materia}', [MateriaController::class, 'show'])->name('materias.show');
    Route::put('/materias/{materia}', [MateriaController::class, 'update'])->name('materias.update');
    Route::delete('/materias/{materia}', [MateriaController::class, 'destroy'])->name('materias.destroy');
    Route::post('/materias', [MateriaController::class, 'store'])->name('materias.store');
    Route::get('/materias/{materia}/edit', [MateriaController::class, 'edit'])->name('materias.edit');
    Route::get('/materias/gestion', [MateriaController::class, 'gestionMaterias'])->name('materias.gestion');
});


Route::middleware('auth')->group(function () {
    Route::get('/salones{$idSalon}/{$nombre}/qr', [QRController::class, 'generateQR']);

    // Ruta para obtener todos los salones
    Route::get('/salones', [SalonController::class, 'index'])->name('salones.index');
    Route::get('/salones/vista', [SalonController::class, 'vista'])->name('salones.vista');

    Route::get('/salones/create', [SalonController::class, 'create'])->name('salones.create');
    // Almacenar un nuevo salón
    Route::put('/salones/{salon}', [SalonController::class, 'update'])->name('salones.update');
    Route::post('/salones/store', [SalonController::class, 'store'])->name('salones.store');
    // Mostrar un salón específico
    //Route::get('/salones/{id}', [SalonController::class, 'show'])->name('salones.show');
    // Mostrar formulario de edición de salón
    Route::get('/salones/{salon}/edit', [SalonController::class, 'edit'])->name('salones.edit');
    // Eliminar un salón
    Route::delete('/salones/{salon}', [SalonController::class, 'destroy'])->name('salones.destroy');
    Route::get('/pisos/por-edificio/{idEdificio}', [SalonController::class, 'getPisosPorEdificio'])->name('pisos.por-edificio');

    Route::get('/salones/gestion', [SalonController::class, 'gestionSalones'])->name('salones.gestion');
    Route::post('/salones/save', [SalonController::class, 'save'])->name('salones.save');
});



// Ruta de política de privacidad
Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

// Registro de administradores
Route::get('/registerAdmin', [AdminController::class, 'showRegisterForm'])->name('registerAdmin');
Route::post('/registerAdmin', [AdminController::class, 'store'])->name('admin.store');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    // Ruta para la página de confirmación
});
Route::get('/confirmation', [ConfirmationController::class, 'show'])->name('confirmation');


Route::middleware('auth')->group(function () {
    // Listar todos los pisos
    Route::get('/pisos', [PisoController::class, 'index'])->name('pisos.index');
    Route::get('/pisos/vista', [PisoController::class, 'vista'])->name('pisos.vista');
    // Mostrar formulario de creación de piso
    Route::get('/pisos/create', [PisoController::class, 'create'])->name('pisos.create');
    // Almacenar un nuevo piso
    Route::post('/pisos', [PisoController::class, 'store'])->name('pisos.store');
    // Mostrar un piso específico
    //Route::get('/pisos/{piso}', [PisoController::class, 'show'])->name('pisos.show');
    // Mostrar formulario de edición de piso
    Route::get('/pisos/{piso}/edit', [PisoController::class, 'edit'])->name('pisos.edit');
    // Actualizar un piso específico
    Route::put('/pisos/{piso}', [PisoController::class, 'update'])->name('pisos.update');
    // Eliminar un piso
    Route::delete('/pisos/{piso}', [PisoController::class, 'destroy'])->name('pisos.destroy');
    Route::get('/pisos/gestion', [PisoController::class, 'gestionPisos'])->name('pisos.gestion');
});



// ----------------- Rutas de Andrés OG (No tocar) ------------- //

Route::middleware('auth')->group(function () {
    // Rutas para API
    Route::get('/api/dashboard-data', [SuperAdminDashboardController::class, 'getDashboardData'])->name('api.dashboard.data');
    Route::get('/api/institution-dashboard-data', [DashboardMainController::class, 'getDashboardData'])->name('api.institution.dashboard.data');

    // Rutas para el superadmin (dashboard)
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/institutions', [SuperAdminDashboardController::class, 'showInstitutions'])->name('institutions');


    // Ruta para la página principal
    Route::get('/main', [MainController::class, 'showMain'])->name('main');
    Route::get('/institution/dashboard', [DashboardMainController::class, 'index'])->name('institution.dashboard');


    Route::get('/maestros-dashboard', [MaestroController::class, 'dashboardStats'])->name('api.maestros.dashboard');
    Route::get('/horarios-dashboard', [HorarioController::class, 'dashboardStats'])->name('api.horarios.dashboard.data');

    Route::get('/pisos/por-edificio/{idEdificio}', [PisoController::class, 'getPisosPorEdificio']);

    Route::put('/grupos-editar/{grupo}', [GrupoController::class, 'update'])->name(name: 'grupos.update');

    Route::get('/eventos-show/{id}', [EventoController::class, 'showEvents'])->name('eventos.showEvents');
});



/* Rutas que pidio Andres
Route::middleware('auth')->group(function(){
    Route::post('/mobile/login', [LoginController::class, 'mobileLogin']);
    Route::post('/mobile/logout', [LoginController::class, 'mobileLogout']);
    Route::get('/api/horarios', [HorarioController::class, 'index'])->name('horarios.index');

    Route::get('/api/horarios/maestro/{maestroId}/dia/{dia}', [HorarioController::class, 'horariosDelMaestroPorDia'])->name('horarios.horariosDelMaestroPorDia');

    Route::get('/api/horarios/salon/{salonId}/momento', [HorarioController::class, 'horarioEnElMomentoDelSalon'])->name('horarios.horarioEnElMomentoDelSalon');
    Route::get('/api/horarios/salon/{salonId}/dia/{dia}', [HorarioController::class, 'horarioDelSalonPorDia'])->name('horarios.horarioDelSalonPorDia');
    Route::get('/api/horarios/salon/{salonId}/semana', [HorarioController::class, 'horarioDelSalonPorSemana'])->name('horarios.horarioDelSalonPorSemana');
    Route::get('/api/horarios/disponibles/hoy', [HorarioController::class, 'todosLosHorariosDisponiblesHoy'])->name('horarios.todosLosHorariosDisponiblesHoy');
});


*/