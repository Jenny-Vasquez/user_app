<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar todas las rutas web para tu aplicación.
| Las rutas de tu aplicación son cargadas por el RouteServiceProvider dentro de un grupo
| que contiene el middleware "web". ¡Ahora construye algo grandioso!
|
*/

// Rutas de autenticación
Auth::routes(['verify' => true]);

// Ruta principal
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

// Rutas del perfil del usuario
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
Route::post('/profile/change-email', [ProfileController::class, 'changeEmail'])->name('profile.changeEmail');
Route::post('/profile/change-username', [ProfileController::class, 'changeUsername'])->name('profile.changeUsername');

// Rutas de usuarios protegidas por autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para la gestión de usuarios (solo administradores)
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

// Rutas de administradores
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
});
