<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminOrderController;
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
//Books
Route::post('/books',[AdminBookController::class,'store']);
Route::get('/books',[AdminBookController::class,'index']);
Route::get('/books/{book}',[AdminBookController::class,'show']);
Route::patch('/books/{book}',[AdminBookController::class,'update']);
Route::delete('/books/{book}',[AdminBookController::class,'destroy']);

//Payments
Route::post('/payment',[PaymentController::class,'submit']);
Route::get('/payment_result',[PaymentController::class,'process']);
Route::get('/payment_result',[PaymentController::class,'process']);

//Orders
Route::get('/orders',[AdminOrderController::class,'index']);
Route::get('/orders/{order}',[AdminOrderController::class,'show'])->where('id', '[0-9]+');

//More details by expounding on the model relationships
Route::get('/orders_details/{id}',[AdminOrderController::class,'order_details']);
Route::get('/orders_details/',[AdminOrderController::class,'all_order_details']);