<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/admin.php';

Route::middleware('auth')->get('/', function () {
    return redirect()->route('dashboard');
})->name('home');
