<?php

use App\Http\Controllers\ProxyCheckController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\ToolController;
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

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('proxies')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProxyController::class, 'index']);
    Route::post('/', [ProxyController::class, 'store']);
    Route::put('/{id}', [ProxyController::class, 'update']);
    Route::delete('/{id}', [ProxyController::class, 'destroy']);

    Route::post('/{id}/check', [ProxyController::class, 'checkNow']);
});

Route::prefix('proxies/checks')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [ProxyCheckController::class, 'index']);
});

Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', [NotificationController::class, 'index']);
});

Route::prefix('bots')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TelegramBotController::class, 'index']);
    Route::post('', [TelegramBotController::class, 'store']);
    Route::delete('/{id}', [TelegramBotController::class, 'destroy']);

    Route::any('/{id}/toggle', [TelegramBotController::class, 'toggle']);
});

Route::prefix('tools')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ToolController::class, 'index']);
});