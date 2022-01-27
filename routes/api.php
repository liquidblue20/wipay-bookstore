<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\BookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/books',[AdminBookController::class,'store']);

Route::get('/books',[AdminBookController::class,'index']);

Route::get('/books/{book}',[AdminBookController::class,'show']);

Route::patch('/books/{book}',[AdminBookController::class,'update']);

Route::delete('/books/{book}',[AdminBookController::class,'destroy']);

Route::post('register',[Authcontroller::class,'register']);

// Route::post('/tokens/create', function (Request $request) {
//     $token = $request->user()->createToken($request->token_name);

//     return ['token' => $token->plainTextToken];
// });