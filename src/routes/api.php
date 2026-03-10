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
})->name('user');

Route::prefix('proxies')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProxyController::class, 'index'])->name('proxies.index');
    Route::post('/', [ProxyController::class, 'store'])->name('proxies.store');
    Route::put('/{proxy}', [ProxyController::class, 'update'])->name('proxies.update');
    Route::delete('/{proxy}', [ProxyController::class, 'destroy'])->name('proxies.destroy');

    Route::post('/{proxy}/check', [ProxyController::class, 'checkNow'])->name('proxies.check');
});

Route::prefix('proxies/checks')->middleware('auth:sanctum')->group(function () {
    Route::get('/{proxy}', [ProxyCheckController::class, 'index'])->name('proxies.checks.index');
});

Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/{proxy}', [NotificationController::class, 'index'])->name('notifications.index');
});

Route::prefix('bots')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TelegramBotController::class, 'index'])->name('bots.index');
    Route::post('/', [TelegramBotController::class, 'store'])->name('bots.store');
    Route::delete('/{bot}', [TelegramBotController::class, 'destroy'])->name('bots.destroy');

    Route::post('/{bot}/toggle', [TelegramBotController::class, 'toggle'])->name('bots.toggle');
});

Route::prefix('tools')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ToolController::class, 'index'])->name('tools.index');
});