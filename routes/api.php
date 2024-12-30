<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

Route::post('/purchase',[SalesController::class, 'purchase']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');