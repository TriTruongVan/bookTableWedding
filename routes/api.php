<?php

use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\AuthAdminUser;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::controller(AuthenticatedController::class)->group(function () {
        Route::post('/admin/login', 'loginAdmin')->name('login.admin');
    });
});

//for Admin 
    //customer
Route::middleware(AuthAdminUser::class)->group(function() {
    Route::controller(CustomerController::class)->prefix('customer')->group(function (){
        Route::get('/', 'getAllCustomer')->name('customer.getAll');
    });
});