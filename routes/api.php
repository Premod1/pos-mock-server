<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

// GET: C# application polls this to retrieve any pending sales
Route::get('/pos-terminal/poll', [PosController::class, 'poll']);

// POST: C# application calls this to update status of a transaction
Route::post('/pos-terminal/update', [PosController::class, 'update']);
