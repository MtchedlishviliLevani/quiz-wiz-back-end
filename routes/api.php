<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest:sanctum')->group(function (): void {
	Route::post('login', [AuthController::class, 'login'])->name('auth.login');
	Route::post('register', [AuthController::class, 'register'])->name('auth.register');
	Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
	Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

Route::middleware('auth:sanctum')->group(function () {
	Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
