<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchLogController;
use App\Http\Middleware\ApiTokenCheck;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('contact', ContactController::class);
        Route::get('restore/{id}', [ContactController::class, 'restore'])->name('contact.restore');
        Route::get('restore-list', [ContactController::class, 'showRestore'])->name('contact.restoreList');
        Route::delete('force-delete/{id}', [ContactController::class, 'forceDelete'])->name('contact.forceDelete');
        Route::post('logout', [ApiAuthController::class, 'logout']);
        Route::post('logout-all', [ApiAuthController::class, 'logoutAll']);
        Route::get('device', [ApiTokenCheck::class, 'device']);
        Route::get('favorite-list', [FavoriteController::class, 'index'])->name('favorite.index');
        Route::post('store-favorite/{contact}', [FavoriteController::class, 'storeFavorite'])->name('favorite.store');
        Route::delete('favorite/{contact}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
        Route::get('search',[SearchLogController::class,'store'])->name('contact.searchLog');
    });

    // Without Auth
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::post('login', [ApiAuthController::class, 'login']);
});
