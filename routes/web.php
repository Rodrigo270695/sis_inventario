<?php

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\MakeController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\SolicitudeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TypeRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;


Route::get('/', function () {
    if (auth()->check()) { // Verifica si el usuario está autenticado
        return redirect('/dashboard'); // Redirige al dashboard
    }

    return redirect('/login'); // Redirige al login si no está autenticado
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

    /* Usuarios */
    Route::get('user/search', [UserController::class, 'search' ])->name('user.search');
    Route::resource('user', UserController::class);
    Route::put('user/change/{user}', [UserController::class, 'change'])->name('user.change');

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
    Route::get('income/team/search', [TeamController::class, 'search' ])->name('team.search');
    Route::resource('income/team', TeamController::class);
    Route::get('/team/download/{file}', function ($file) {
        $pathToFile = public_path('storage/documentos/' . $file);
        if (file_exists($pathToFile)) {
            return Response::download($pathToFile);
        } else {
            abort(404, 'Archivo no encontrado.');
        }
    });
    Route::put('/team/update/document/{id}', [TeamController::class, 'updateDocument'])->name('team.update.document');

    /* Eccesorios */
    Route::get('income/accessory/search', [AccessoryController::class, 'search' ])->name('accessory.search');
    Route::resource('income/accessory', AccessoryController::class);
    Route::get('/accessory/download/{file}', function ($file) {
        $pathToFile = public_path('storage/documentos/' . $file);
        if (file_exists($pathToFile)) {
            return Response::download($pathToFile);
        } else {
            abort(404, 'Archivo no encontrado.');
        }
    });
    Route::put('/accessory/update/document/{id}', [AccessoryController::class, 'updateDocument'])->name('accessory.update.document');
    Route::post('/accessories/assign-to-team', [AccessoryController::class, 'assignToTeam'])->name('accessory.assignToTeam');

    /* Operaciones */
    /* tipos */
    Route::get('operation/typer/search', [TypeRequestController::class, 'search' ])->name('typer.search');
    Route::resource('operation/typer', TypeRequestController::class);
    Route::put('operation/typer/change/{type}', [TypeRequestController::class, 'change'])->name('typer.change');

    /* solicitud */
    Route::get('operation/solicitude/search', [SolicitudeController::class, 'search' ])->name('solicitude.search');
    Route::resource('operation/solicitude', SolicitudeController::class);
    Route::put('operation/solicitude/approve/{solicitude}', [SolicitudeController::class, 'approve'])->name('solicitude.approve');
    Route::put('operation/solicitude/reject/{solicitude}', [SolicitudeController::class, 'reject'])->name('solicitude.reject');
    Route::put('operation/solicitude/approveManagement/{solicitude}', [SolicitudeController::class, 'approveManagement'])->name('solicitude.approveManagement');
    Route::put('operation/solicitude/rejectManagement/{solicitude}', [SolicitudeController::class, 'rejectManagement'])->name('solicitude.rejectManagement');

    /* Traslados */
    Route::resource('operation/move', SolicitudeController::class);
});
