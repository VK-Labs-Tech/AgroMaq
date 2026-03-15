<?php

use App\Http\Controllers\Admin\DiarioBordoController;
use App\Http\Controllers\Admin\DiarioBordoSyncController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', function () {
        return redirect()->route('admin.diario-bordo.index');
    })->name('home');

    Route::prefix('diario-bordo')->name('diario-bordo.')->group(function (): void {
        Route::get('/', [DiarioBordoController::class, 'index'])->name('index');
        Route::get('/novo', [DiarioBordoController::class, 'create'])->name('create');
        Route::get('/{id}', [DiarioBordoController::class, 'show'])->name('show');
        Route::post('/{id}/pre-viagem', [DiarioBordoController::class, 'salvarPreViagem'])->name('pre-viagem');
        Route::post('/{id}/checklist', [DiarioBordoController::class, 'salvarChecklist'])->name('checklist');
        Route::post('/{id}/transito', [DiarioBordoController::class, 'registrarTransito'])->name('transito');
        Route::post('/{id}/encerramento', [DiarioBordoController::class, 'encerramento'])->name('encerramento');
        Route::get('/{id}/gps-feed', [DiarioBordoController::class, 'gpsFeed'])->name('gps-feed');
    });

    Route::post('/diario-bordo/sync', [DiarioBordoSyncController::class, 'sync'])->name('diario-bordo.sync');
});
