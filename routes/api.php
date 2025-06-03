<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

Route::middleware('auth:api')->group(function () {

    // Untuk admin saja
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/books', BookController::class)->only(['store', 'update', 'destroy']);
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);
        // Route lain yang khusus admin...
    });

    // Untuk customer saja
    Route::middleware('role:customer')->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update']);
    });

    // Untuk user yang sudah login (admin & customer) bisa akses genres read-only
    Route::get('/genres', [GenreController::class, 'index']);
});
