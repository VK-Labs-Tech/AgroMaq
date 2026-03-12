<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuelRecordController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('machines', MachineController::class);
Route::resource('operators', OperatorController::class);
Route::resource('work-logs', WorkLogController::class);
Route::resource('fuel-records', FuelRecordController::class);
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');Route::get('/maintenances/preventive-launch', [MaintenanceController::class, 'preventiveLaunch'])
	->name('maintenances.preventive-launch');
Route::post('/maintenances/preventive-launch', [MaintenanceController::class, 'storePreventiveLaunch'])
	->name('maintenances.preventive-launch.store');
Route::resource('maintenances', MaintenanceController::class);

Route::get('/reports/operational-costs', [ReportController::class, 'operationalCosts'])->name('reports.operational-costs');
