<?php

use App\Http\Controllers\AuthenticatedController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::controller(AuthenticatedController::class)->group(function () {
        Route::post('/admin/login', 'loginAdmin')->name('login.admin');
    });
});