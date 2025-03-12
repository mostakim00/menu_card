<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ItemController;
use App\Http\Controllers\Web\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/category', [ItemController::class, 'index'])->name('item.index');
    Route::get('/category/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/category/store', [ItemController::class, 'store'])->name('item.store');
    Route::get('/category/edit/{id}', [ItemController::class, 'edit'])->name('item.edit');
    Route::post('/category/update/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::delete('/category/destroy/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
});
