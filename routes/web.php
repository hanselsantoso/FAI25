<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
Route::get('/admin/suppliers', [AdminController::class, 'suppliers'])->name('admin.suppliers.index');
