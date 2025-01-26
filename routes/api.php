<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\LoginController;

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/user/current', [UserController::class, 'authUser'])->name('user.current');
    Route::post('/refresh', [LoginController::class, 'refresh'])->name('refresh');
    Route::get('/users/reviews', [UserReviewController::class, 'index'])->name('users.reviews.index');
    Route::post('/users/reviews/{product}', [UserReviewController::class, 'store'])->name('user.reviews.store');
    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class)->except(['index']);
    Route::apiResource('reviews', ReviewController::class);
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');





/*Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});*/





