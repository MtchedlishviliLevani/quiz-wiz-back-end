<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/difficulties', [DifficultyController::class, 'index'])->name('difficulties');

Route::middleware('guest:sanctum')->group(function (): void {
	Route::post('/login', LoginController::class)->name('auth.login')->middleware("throttle:6,1");
	Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
	Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
	Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

Route::middleware('auth:sanctum')->group(function (): void {
	Route::post('logout', LogoutController::class);
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::prefix('quizzes')->group(function (): void {
	Route::get('/', [QuizController::class, 'index']);
	Route::get('/{quiz}', [QuizController::class, 'show']);
	Route::get('/{quiz}/questions', [QuizController::class, 'QuizQuestions']);
	Route::post('/submit', [QuizController::class, 'submitQuiz']);
});

Route::get('/contacts', [UserContactController::class, 'index'])->name('user-contacts');
