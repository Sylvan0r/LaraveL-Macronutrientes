<?php

use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/status', function (){
    return response()->json([
        'status' => 'ok',
        'laravel' => app()->version(),
    ]);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('products', ApiProductController::class);    
    Route::post('/createProduct', [ApiProductController::class, 'store']);
});

Route::post('/login', [UserController::class, 'index']);