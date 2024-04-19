<?php

use App\Http\Controllers\PdvController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::resource('user', UserController::class);
    /* Zonal */
    Route::get('zona/zonal/search', [ZonalController::class, 'search' ])->name('zonal.search');
    Route::resource('zona/zonal', ZonalController::class);
    Route::put('zona/zonal/change/{zonal}', [ZonalController::class, 'change'])->name('zonal.change');

    Route::resource('zona/pdv', PdvController::class);

});
