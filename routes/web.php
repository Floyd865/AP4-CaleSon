<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManifestationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/manifestations', [ManifestationController::class, 'index'])->name('manifestations.index');
Route::get('/manifestations/{id}', [ManifestationController::class, 'show'])->name('manifestations.show');