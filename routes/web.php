<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DewormingController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\QRCodeController;
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

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Protected application (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/mypets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
    Route::get('/register-pet', [PetController::class, 'create'])->name('pets.create');
    Route::post('/register-pet', [PetController::class, 'store'])->name('pets.store');

    Route::get('/vaccinations', [VaccinationController::class, 'index'])->name('vaccinations.index');
    Route::post('/vaccinations', [VaccinationController::class, 'store'])->name('vaccinations.store');
    Route::get('/deworming', [DewormingController::class, 'index'])->name('deworming.index');
    Route::post('/deworming', [DewormingController::class, 'store'])->name('deworming.store');
    Route::get('/qrcodes', [QRCodeController::class, 'index'])->name('qrcodes.index');
    Route::get('/qrcodes/{pet}/download', [QRCodeController::class, 'show'])->name('qrcodes.download');
    Route::get('/qrcodes/{pet}/preview', [QRCodeController::class, 'preview'])->name('qrcodes.preview');
});
