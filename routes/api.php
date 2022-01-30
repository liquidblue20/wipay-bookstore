<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\admin\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')-> group(function() {
    route::post('/logout',[AuthController::class,'logout']);

    Route::middleware('auth:sanctum','ability:purchase:book')-> group(function() {
        //Payments
        Route::post('/payment',[PaymentController::class,'submit']);
        
    });

    Route::middleware('auth:sanctum','ability:view:sales')-> group(function() {
        //Orders
        Route::get('/orders',[AdminOrderController::class,'index']);
        Route::get('/orders/{order}',[AdminOrderController::class,'show']);
        //More details by expounding on the model relationships
        Route::get('/orders_details/{id}',[AdminOrderController::class,'order_details']);
        Route::get('/orders_details/',[AdminOrderController::class,'all_order_details']);
    });
    
    Route::middleware('auth:sanctum','ability:crud:book')-> group(function() {
        //Admin Books
        Route::post('/books',[AdminBookController::class,'store']);
        Route::patch('/books/{book}',[AdminBookController::class,'update']);
        Route::delete('/books/{book}',[AdminBookController::class,'destroy']);
    });
});

//Auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

//Books
route::get('/books',[BookController::class,'index']);
Route::get('/books/{book}',[BookController::class,'show']);
Route::get('/books/search/{title}',[BookController::class,'search']);
Route::get('/books',[BookController::class,'index']);

Route::get('/payment_result',[PaymentController::class,'process']);