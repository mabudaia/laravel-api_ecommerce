<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){

Route::post('logout',[UserController::class,'logout']);
Route::apiResource('products', ProductController::class);
Route::get('getAllProuduct', [ProductController::class,'getAllProuduct']);
Route::get('show', [ProductController::class,'show']);
Route::apiResource('orders', OrderController::class);
Route::post('/products/{product}/discount', [ProductController::class, 'applyDiscount']);
Route::apiResource('cart', CartController::class);

Route::apiResource('profile', ProfileController::class);
Route::get('/profileUser',[ UserController::class,'getProfile']);


});
Route::post('products/{productsId}/categories',[ProductController::class, 'addCategoryToProduct']);
Route::get('categories/{CategoryId}/products',[ProductController::class, 'getCategoryProducts']);
