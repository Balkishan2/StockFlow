<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use App\Http\Middleware\ValidUser;

Route::get('/', function () {
    return view('login');
});


// Route::middleware
Route::match(['get', 'post'],'/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::match(['get', 'post'],'/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');



Route::match(['get', 'post'],'/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware(ValidUser::class);



