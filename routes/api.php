<?php

use App\Http\Controllers\AuthenticatedController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\AuthAdminUser;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::controller(AuthenticatedController::class)->group(function () {
        Route::post('/admin/login', 'loginAdmin')->name('login.admin');
    });
});

//for Admin 
Route::middleware(AuthAdminUser::class)->group(function() {
    //customer
    Route::controller(CustomerController::class)->prefix('customer')->group(function (){
        Route::get('/', 'getAllCustomer')->name('customer.getAll');
        Route::get('/search', 'searchCustomer')->name('customer.search');
        Route::post('/create', 'createCustomer')->name('customer.create');
        Route::get('/wards', 'getAllWards')->name('customer.getAllWards');
        Route::get('/{customer}', 'getById')->name('customer.getById');
        Route::put('/{customer}/update', 'updateCustomer')->name('customer.update');
    });
    //voucher
    Route::controller(VoucherController::class)->prefix('voucher')->group(function(){
        Route::get('/all', 'getAllVoucher')->name('voucher.getAll');
        Route::get('/', 'get')->name('voucher.get');
        Route::post('/create', 'createVoucher')->name('voucher.create');
        Route::get('/{voucher}', 'getById')->name('voucher.getById');
        Route::put('/{voucher}/update', 'updateVoucher')->name('voucher.update');
    });
    //staff
    Route::controller(StaffController::class)->prefix('staff')->group(function(){
        Route::get('/', 'getAll')->name('staff.get');
        Route::get('/search', 'searchStaff')->name('staff.search');
        Route::post('/create', 'createStaff')->name('staff.create');
        Route::get('/{staff}', 'getById')->name('staff.getById');
        Route::put('/{staff}/update', 'updateStaff')->name('staff.update');
    });
});