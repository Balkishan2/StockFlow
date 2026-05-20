<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemInventoryController;
use App\Http\Controllers\CustomerController;
use \App\Http\Controllers\InvoiceController;
use \App\Http\Controllers\ItemController;
use \App\Http\Controllers\SaleInvoiceController;
use App\Http\Middleware\ValidUser;

Route::get('/', function () {
    return view('login');
});


// Route::middleware
Route::match(['get', 'post'],'/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::match(['get', 'post'],'/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');



Route::match(['get', 'post'],'/dashboard', [UserController::class, 'dashboard'])->name('dashboard')->middleware(ValidUser::class);

Route::match(['get', 'post'],'/customers', [CustomerController::class, 'index'])->name('customers')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/customers/add', [CustomerController::class, 'add'])->name('customers.add')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit')->middleware(ValidUser::class);
Route::get('/customers/{id}/delete', [CustomerController::class, 'delete'])->name('customers.delete')->middleware(ValidUser::class);

Route::match(['get', 'post'],'/orders', [OrderController::class, 'index'])->name('orders')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/orders/add', [OrderController::class, 'add'])->name('orders.add')->middleware(ValidUser::class);
Route::get('/orders/{id}/invoice', [InvoiceController::class, 'show'])->name('orders.invoice')->middleware(ValidUser::class);
Route::get('/orders/{id}/view', [OrderController::class, 'show'])->name('orders.view')->middleware(ValidUser::class);
Route::match(['get', 'post'], '/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit')->middleware(ValidUser::class);

Route::match(['get', 'post'],'/invoices', [SaleInvoiceController::class, 'index'])->name('invoices')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/invoices/add', [SaleInvoiceController::class, 'add'])->name('invoices.add')->middleware(ValidUser::class);
Route::match(['get', 'post'],'/invoices/{id}/edit', [SaleInvoiceController::class, 'edit'])->name('invoices.edit')->middleware(ValidUser::class);
Route::get('/invoices/{id}/print', [SaleInvoiceController::class, 'print'])->name('invoices.print')->middleware(ValidUser::class);
Route::get('/invoices/{id}/delete', [SaleInvoiceController::class, 'delete'])->name('invoices.delete')->middleware(ValidUser::class);

Route::match(['get', 'post'],'/inventory', [ItemInventoryController::class, 'index'])->name('inventory')->middleware(ValidUser::class);
Route::match(['get', 'post'], '/item-inventory/add', [ItemInventoryController::class, 'add'])->name('inventory.add')->middleware(ValidUser::class);

Route::match(['get', 'post'], '/products/listing', [ItemController::class, 'index'])->name('products.listing')->middleware(ValidUser::class);
Route::match(['get', 'post'], '/products/add', [ItemController::class, 'add'])->name('products.add')->middleware(ValidUser::class);
Route::match(['get', 'post'], '/products/{id}/edit', [ItemController::class, 'edit'])->name('products.edit')->middleware(ValidUser::class);
Route::get('/products/{id}/delete', [ItemController::class, 'delete'])->name('products.delete')->middleware(ValidUser::class);

