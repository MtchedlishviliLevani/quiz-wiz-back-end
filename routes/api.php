<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
