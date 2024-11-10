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

// Ruta para la página de inicio
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Rutas de inicio de sesión
Route::view('/login', 'login')->name('login'); // Asegúrate de que la vista 'login' exista
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [RegisterInstitutionController::class, 'showInstitutionForm'])->name('register'); // Ruta para mostrar el formulario de registro
Route::post('/register', [RegisterInstitutionController::class, 'storeInstitution'])->name('institution.store'); // Ruta para almacenar el nombre de la institución

// Rutas de pago
Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.show'); // Ruta para mostrar el formulario de pago
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process'); // Ruta para procesar el pago

// Ruta para mostrar la política de privacidad
Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

// Rutas de registro de administrador
Route::get('/registerAdmin', [AdminController::class, 'showRegisterForm'])->name('registerAdmin'); // Mostrar formulario de registro del administrador
Route::post('/registerAdmin', [AdminController::class, 'store'])->name('admin.store'); // Almacenar administrador

// Rutas de gestión de usuarios por parte del administrador
Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users'); // Listar usuarios
Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit'); // Mostrar formulario de edición de usuario
Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update'); // Actualizar usuario
Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle.status'); // Cambiar el estado de acceso de usuario

// Ruta para mostrar la pantalla principal
Route::get('/main', [MainController::class, 'showMain'])->name('main'); // Ruta para mostrar la página principal

// Ruta para mostrar la página de confirmación
Route::get('/confirmation', [ConfirmationController::class, 'show'])->name('confirmation');

// Rutas del superadmin
Route::get('/dashboard', [SuperAdminDashboardController::class, 'showDashboard'])->name('dashboard'); // Mostrar el dashboard del superadmin


Route::post('/admin/users/{id_user}/status', [AdminController::class, 'updateStatus'])->name('admin.user.status.update');
Route::delete('/admin/users/{id_user}', [AdminController::class, 'destroy'])->name('admin.user.delete');



//Agregar carreras
Route::get('/carreras/create', [CarreraController::class, 'create'])->name('carreras.create');
Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');
Route::get('/carreras', [CarreraController::class, 'index'])->name('carreras.index');
Route::get('/carreras/{id}/edit', [CarreraController::class, 'edit'])->name('carreras.edit');
Route::put('/carreras/{id}', [CarreraController::class, 'update'])->name('carreras.update');

// Listar todos los maestros
Route::get('/maestros', [MaestroController::class, 'index'])->name('maestros.index');
// Mostrar el formulario para crear un nuevo maestro
Route::get('/maestros/create', [MaestroController::class, 'create'])->name('maestros.create');
// Guardar un nuevo maestro
Route::post('/maestros', [MaestroController::class, 'store'])->name('maestros.store');
// Mostrar el formulario para editar un maestro específico
Route::get('/maestros/{maestro}/edit', [MaestroController::class, 'edit'])->name('maestros.edit');
// Actualizar un maestro específico
Route::put('/maestros/{maestro}', [MaestroController::class, 'update'])->name('maestros.update');
// Eliminar un maestro específico
Route::delete('/maestros/{maestro}', [MaestroController::class, 'destroy'])->name('maestros.destroy');

// Listar todas las materias
Route::get('/materias', [MateriaController::class, 'index'])->name('materias.index');
// Mostrar el formulario para crear una nueva materia
Route::get('/materias/create', [MateriaController::class, 'create'])->name('materias.create');
// Guardar una nueva materia
Route::post('/materias', [MateriaController::class, 'store'])->name('materias.store');
// Mostrar el formulario para editar una materia específica
Route::get('/materias/{materia}/edit', [MateriaController::class, 'edit'])->name('materias.edit');
// Actualizar una materia específica
Route::put('/materias/{materia}', [MateriaController::class, 'update'])->name('materias.update');
// Eliminar una materia específica
Route::delete('/materias/{materia}', [MateriaController::class, 'destroy'])->name('materias.destroy');


Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
Route::get('/grupos/{grupo}/edit', [GrupoController::class, 'edit'])->name('grupos.edit');
Route::put('/grupos/{grupo}', [GrupoController::class, 'update'])->name('grupos.update');
Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');

// Salones
Route::get('/salones', [SalonController::class, 'index'])->name('salones.index');
Route::get('/salones/create', [SalonController::class, 'create'])->name('salones.create');
Route::post('/salones', [SalonController::class, 'store'])->name('salones.store');
Route::get('/salones/{salon}/edit', [SalonController::class, 'edit'])->name('salones.edit');
Route::put('/salones/{salon}', [SalonController::class, 'update'])->name('salones.update');

// Pisos
Route::get('/pisos', [PisoController::class, 'index'])->name('pisos.index');
Route::get('/pisos/create', [PisoController::class, 'create'])->name('pisos.create');
Route::post('/pisos', [PisoController::class, 'store'])->name('pisos.store');
Route::get('/pisos/{piso}/edit', [PisoController::class, 'edit'])->name('pisos.edit');
Route::put('/pisos/{piso}', [PisoController::class, 'update'])->name('pisos.update');

// Edificios
Route::get('/edificios', [EdificioController::class, 'index'])->name('edificios.index');
Route::get('/edificios/create', [EdificioController::class, 'create'])->name('edificios.create');
Route::post('/edificios', [EdificioController::class, 'store'])->name('edificios.store');
Route::get('/edificios/{edificio}/edit', [EdificioController::class, 'edit'])->name('edificios.edit');
Route::put('/edificios/{edificio}', [EdificioController::class, 'update'])->name('edificios.update');
//Route::delete('/edificios/{edificio}', [EdificioController::class, 'destroy'])->name('edificios.destroy');

// Horarios
Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
Route::get('/horarios/create', [HorarioController::class, 'create'])->name('horarios.create');
Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
Route::get('/horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('horarios.edit');
Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

// Eventos
Route::get('eventos', [EventoController::class, 'index'])->name('eventos.index');
Route::get('eventos/create', [EventoController::class, 'create'])->name('eventos.create');
Route::post('eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::get('eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
Route::put('eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
Route::delete('eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');