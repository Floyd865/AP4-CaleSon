<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManifestationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvisController;
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

// Routes pour Concert/Conférence/Exposition (sans date)
Route::get('/manifestations/{type}/{id}', [ManifestationController::class, 'show'])
    ->where('type', 'concert|conference|exposition')
    ->name('manifestations.show');

// Routes pour Atelier (avec date obligatoire pour identifier la séance)
Route::get('/manifestations/atelier/{id}/{date}', [ManifestationController::class, 'showAtelier'])
    ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
    ->name('manifestations.show.atelier');

// Routes de réservation (nécessitent l'authentification)
Route::middleware('auth')->group(function () {
    // Réservations pour Concert/Conférence/Exposition
    Route::get('/manifestations/{type}/{id}/reserver', [ReservationController::class, 'create'])
        ->where('type', 'concert|conference|exposition')
        ->name('reservations.create');
    Route::post('/manifestations/{type}/{id}/reserver', [ReservationController::class, 'store'])
        ->where('type', 'concert|conference|exposition')
        ->name('reservations.store');
    
    Route::get('/manifestations/{type}/{id}/reserver-payant', [ReservationController::class, 'createPayant'])
        ->where('type', 'concert|conference|exposition')
        ->name('reservations.create-payant');
    Route::post('/manifestations/{type}/{id}/reserver-payant', [ReservationController::class, 'storePayant'])
        ->where('type', 'concert|conference|exposition')
        ->name('reservations.store-payant');
    
    // Réservations pour Atelier (avec date)
    Route::get('/manifestations/atelier/{id}/{date}/reserver', [ReservationController::class, 'createAtelier'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('reservations.create.atelier');
    Route::post('/manifestations/atelier/{id}/{date}/reserver', [ReservationController::class, 'storeAtelier'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('reservations.store.atelier');
    
    Route::get('/manifestations/atelier/{id}/{date}/reserver-payant', [ReservationController::class, 'createPayantAtelier'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('reservations.create-payant.atelier');
    Route::post('/manifestations/atelier/{id}/{date}/reserver-payant', [ReservationController::class, 'storePayantAtelier'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('reservations.store-payant.atelier');
    
    // Voir mes réservations
    Route::get('/mes-reservations', [ReservationController::class, 'mesReservations'])->name('reservations.index');
    
    // Routes pour les avis
    // Avis pour Concert/Conférence/Exposition
    Route::get('/manifestations/{type}/{id}/avis/create', [AvisController::class, 'create'])
        ->where('type', 'concert|conference|exposition')
        ->name('avis.create');
    Route::post('/manifestations/{type}/{id}/avis', [AvisController::class, 'store'])
        ->where('type', 'concert|conference|exposition')
        ->name('avis.store');
    
    // Avis pour Atelier
    Route::get('/manifestations/atelier/{id}/{date}/avis/create', [AvisController::class, 'create'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('avis.create.atelier');
    Route::post('/manifestations/atelier/{id}/{date}/avis', [AvisController::class, 'store'])
        ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
        ->name('avis.store.atelier');
    
    // Modifier et supprimer un avis
    Route::get('/avis/{idavis}/edit', [AvisController::class, 'edit'])->name('avis.edit');
    Route::put('/avis/{idavis}', [AvisController::class, 'update'])->name('avis.update');
    Route::delete('/avis/{idavis}', [AvisController::class, 'destroy'])->name('avis.destroy');
});