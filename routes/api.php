<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::get('categories', [CategoryController::class, 'index']);
Route::get('difficulties', [DifficultyController::class, 'index']);

Route::get('quizzes', [QuizController::class, 'index']);

Route::middleware('guest:sanctum')->group(function () {
	Route::post('login', [AuthController::class, 'login']);
	Route::post('register', [AuthController::class, 'register']);
});
// Route::post('login', [AuthController::class, 'login']);
// Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('logout', [AuthController::class, 'logout']);
});
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::middleware(['guest:sanctum'])->post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware(['web', 'guest:sanctum'])->post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
