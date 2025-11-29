<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Shortening Route
Route::middleware('auth:sanctum')->post('/shorten', [LinkController::class, 'apiStore'])->name('api.shorten');
