<?php

use Illuminate\Support\Facades\Route;
use VedaTrace\Laravel\Facades\VedaTrace;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Sample routes showing VedaTrace usage in a Laravel application.
|
*/

Route::get('/', function () {
    // 1. Logging via the VedaTrace Facade
    VedaTrace::info('Home page visited', [
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return view('welcome');
});

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    // 2. Logging with sensitive data (automatic redaction will mask the password)
    VedaTrace::info('Login attempt', [
        'email' => $credentials['email'],
        'password' => $credentials['password'] // Redacted!
    ]);

    return redirect('/dashboard');
});

Route::get('/error', function () {
    try {
        throw new \Exception('Houston, we have a problem!');
    } catch (\Exception $e) {
        // 3. Simple error logging
        VedaTrace::error('An unexpected error occurred', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    return response('Error logged', 500);
});
