<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ValidUser;

Route::get('/', function () {
    return view('login');
});


// Route::middleware
Route::match(['get', 'post'],'/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::match(['get', 'post'],'/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');



Route::match(['get', 'post'],'/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware(ValidUser::class);

Route::match(['get', 'post'],'/orders', [OrderController::class, 'index'])->name('orders')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/orders/add', [OrderController::class, 'add'])->name('orders.add')->middleware(ValidUser::class);
Route::get('/orders/{id}/invoice', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('orders.invoice')->middleware(ValidUser::class);
Route::get('/orders/{id}/view', [OrderController::class, 'show'])->name('orders.view')->middleware(ValidUser::class);
Route::match(['get', 'post'], '/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit')->middleware(ValidUser::class);

