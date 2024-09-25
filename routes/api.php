<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth.api'], function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::get('/{id}/mutasi', [UserController::class, 'showMutasi']);
        Route::post('/{id}/edit', [UserController::class, 'edit']);
        Route::post('/create', [UserController::class, 'create']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    Route::prefix('mutasi')->group(function () {
        Route::get('/', [MutasiController::class, 'index']);
        Route::get('/{id}', [MutasiController::class, 'show']);
        Route::post('/{id}/edit', [MutasiController::class, 'update']);
        Route::post('/create', [MutasiController::class, 'create']);
        Route::delete('/{id}', [MutasiController::class, 'delete']);
    });

    Route::prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/mutasi', [BarangController::class, 'showMutasi']);
        Route::post('/{id}/edit', [BarangController::class, 'update']);
        Route::post('/create', [BarangController::class, 'create']);
        Route::delete('/{id}', [BarangController::class, 'delete']);
    });
});
