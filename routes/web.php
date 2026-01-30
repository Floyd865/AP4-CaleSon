<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManifestationController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Routes publiques des manifestations
Route::get('/manifestations', [ManifestationController::class, 'index'])->name('manifestations.index');
Route::get('/manifestations/{id}', [ManifestationController::class, 'show'])->name('manifestations.show');

// Routes de réservation (nécessitent l'authentification)
Route::middleware('auth')->group(function () {
    // Réservations GRATUITES
    Route::get('/manifestations/{id}/reserver', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/manifestations/{id}/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    
    // Réservations PAYANTES
    Route::get('/manifestations/{id}/reserver-payant', [ReservationController::class, 'createPayant'])->name('reservations.create-payant');
    Route::post('/manifestations/{id}/reserver-payant', [ReservationController::class, 'storePayant'])->name('reservations.store-payant');
    
    // Voir mes réservations
    Route::get('/mes-reservations', [ReservationController::class, 'mesReservations'])->name('reservations.index');
});