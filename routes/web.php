<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DewormingController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VaccinationController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

// Guest (unauthenticated) routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Shared authenticated routes (read-only for pet owners, richer for staff/admin)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mypets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('/vaccinations', [VaccinationController::class, 'index'])->name('vaccinations.index');
    Route::get('/deworming', [DewormingController::class, 'index'])->name('deworming.index');
    Route::get('/qrcodes', [QRCodeController::class, 'index'])->name('qrcodes.index');
    Route::get('/qrcodes/{pet}/download', [QRCodeController::class, 'show'])->name('qrcodes.download');
    Route::get('/qrcodes/{pet}/preview', [QRCodeController::class, 'preview'])->name('qrcodes.preview');
});

// OCV Staff routes (staff + admin)
Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/users', [StaffController::class, 'users'])->name('staff.users');
    Route::get('/users/{user}', [StaffController::class, 'userDetails'])->name('staff.users.show');
    Route::get('/pets', [StaffController::class, 'allPets'])->name('staff.pets');
    Route::get('/pets/create', [StaffController::class, 'createPet'])->name('staff.pets.create');
    Route::post('/pets', [StaffController::class, 'storePet'])->name('staff.pets.store');
    Route::post('/vaccinations', [VaccinationController::class, 'store'])->name('vaccinations.store');
    Route::post('/deworming', [DewormingController::class, 'store'])->name('deworming.store');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
