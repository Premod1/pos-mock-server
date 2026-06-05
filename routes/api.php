<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

// GET: C# application polls this to retrieve any pending sales
Route::get('/pos-terminal/poll', [PosController::class, 'poll']);

// POST: C# application calls this to update status of a transaction
Route::post('/pos-terminal/update', [PosController::class, 'update']);

// POST: C# application calls this to upload logs
Route::post('/pos-terminal/log', [\App\Http\Controllers\PosTerminalController::class, 'storeLog']);
