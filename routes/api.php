<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\Api\WordPressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Shortening Route
Route::middleware('auth:sanctum')->post('/shorten', [LinkController::class, 'apiStore'])->name('api.shorten');

// WordPress Integration API Routes
Route::prefix('wp')->group(function () {
    Route::get('/link/{code}', [WordPressController::class, 'getLink']);
    Route::post('/click', [WordPressController::class, 'recordClick']);
    Route::get('/test', [WordPressController::class, 'testConnection']);
});
