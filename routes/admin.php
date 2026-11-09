<?php

use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'system.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::post('/restaurants/{restaurant}/approve', [RestaurantController::class, 'approve'])->name('restaurants.approve');
});
