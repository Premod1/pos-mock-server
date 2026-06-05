<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

// Dashboard UI
Route::get('/', [PosController::class, 'index'])->name('dashboard');

// Update Terminal Config
Route::post('/terminal', [PosController::class, 'updateTerminal'])->name('terminal.update');

// Create New Sale
Route::post('/sale', [PosController::class, 'storeSale'])->name('sale.store');

// AJAX: Get Sales Log JSON
Route::get('/sales-data', [PosController::class, 'getSalesData'])->name('sales.data');

// Dashboard Logs UI
Route::get('/terminal-logs', [\App\Http\Controllers\PosTerminalController::class, 'index'])->name('terminal-logs.index');

// AJAX: Get Terminal Logs JSON
Route::get('/terminal-logs-data', [\App\Http\Controllers\PosTerminalController::class, 'getLogsData'])->name('terminal-logs.data');

// Clear Terminal Logs
Route::post('/terminal-logs/clear', [\App\Http\Controllers\PosTerminalController::class, 'clearLogs'])->name('terminal-logs.clear');
