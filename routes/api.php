<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\FirebaseTokenController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Item
Route::post('/item/store',[ItemController::class,'store']);
Route::get('/getItem',[ItemController::class,'getItemData']);
Route::get('/show/item/{id}',[ItemController::class,'show']);
Route::post('/item/update/{id}',[ItemController::class,'update']);
Route::post('/item/delete/{id}',[ItemController::class,'destroy']);

//Product
Route::post('/product/store',[ProductController::class,'store']);
Route::post('/product/update/{id}',[ProductController::class,'update']);
Route::get('/show/product/{id}',[ProductController::class,'show']);
Route::get('/getProduct',[ProductController::class,'getProductData']);
Route::post('/product/delete/{id}',[ProductController::class,'destroy']);


//Order
Route::post('/order/store',[OrderController::class,'store']);
Route::get('/order/show/{id}',[OrderController::class,'getOrderProducts']);
Route::get('/order/getAll',[OrderController::class,'getAllOrdersWithProductDetails']);
Route::post('/order/inprocess/{id}',[OrderController::class,'inprocessState']);
Route::post('/order/done/{id}',[OrderController::class,'doneState']);
Route::get('/order/get/withoutdonestate',[OrderController::class,'getAllOrdersWithProductDetails']);

//Token
Route::post('/firebase/token/store',[FirebaseTokenController::class,'store']);
Route::post('/firebase/token/get',[FirebaseTokenController::class,'getToken']);
Route::post('/firebase/token/delete',[FirebaseTokenController::class,'deleteToken']);


