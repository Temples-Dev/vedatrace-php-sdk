<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VedaTraceDemoController;

Route::get('/', [VedaTraceDemoController::class, 'index'])->name('demo.index');
Route::post('/api/send-log', [VedaTraceDemoController::class, 'sendLog'])->name('api.send_log');
Route::get('/api/simulate-error', [VedaTraceDemoController::class, 'simulateError'])->name('api.simulate_error');
