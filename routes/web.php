<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\MakeController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

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

    /* Pdv */
    Route::get('zona/pdv/search', [PdvController::class, 'search' ])->name('pdv.search');
    Route::resource('zona/pdv', PdvController::class);
    Route::put('zona/pdv/change/{pdv}', [PdvController::class, 'change'])->name('pdv.change');

    /* Store */
    Route::get('zona/store/search', [StoreController::class, 'search' ])->name('store.search');
    Route::resource('zona/store', StoreController::class);
    Route::post('zona/create/storepdv', [StoreController::class, 'createStore'])->name('store.createstore');
    Route::put('zona/store/change/{store}', [StoreController::class, 'change'])->name('store.change');

    /* Make */
    Route::get('maker/make/search', [MakeController::class, 'search' ])->name('make.search');
    Route::resource('maker/make', MakeController::class);
    Route::put('maker/make/change/{make}', [MakeController::class, 'change'])->name('make.change');

    /* types */
    Route::get('maker/type/search', [EquipmentTypeController::class, 'search' ])->name('type.search');
    Route::resource('maker/type', EquipmentTypeController::class);
    Route::post('maker/type/storetype', [EquipmentTypeController::class, 'createType'])->name('type.createstore');

    /* Ingresos */
    /* Equipos */
    Route::resource('income/team', TeamController::class);
    Route::get('/team/download/{file}', function ($file) {
        $pathToFile = public_path('storage/documentos/' . $file);
        if (file_exists($pathToFile)) {
            return Response::download($pathToFile);
        } else {
            abort(404, 'Archivo no encontrado.');
        }
    });
    /* Eccesorios */
    Route::resource('income/accessory', AccessoryController::class);
});
