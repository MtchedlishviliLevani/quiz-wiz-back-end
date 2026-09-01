<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyController;
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
	Route::post('/login', LoginController::class)->name('auth.login')->middleware('throttle:6,1');
	Route::post('/register', RegisterController::class)->name('auth.register')->middleware('throttle:register');
	Route::post('/forgot-password', ForgotPasswordController::class)->name('auth.forgot-password')->middleware('throttle:password-reset');
	Route::post('/reset-password', ResetPasswordController::class)->name('auth.reset-password')->middleware('throttle:password-reset');
});

Route::middleware('auth:sanctum')->group(function (): void {
	Route::post('logout', LogoutController::class);
});

Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::prefix('quizzes')->group(function (): void {
	Route::get('/', [QuizController::class, 'index']);
	Route::get('/{quiz}', [QuizController::class, 'show']);
	Route::get('/{quiz}/questions', [QuizController::class, 'QuizQuestions']);
	Route::post('/submit', [QuizController::class, 'submitQuiz']);
});

Route::get('/contacts', [UserContactController::class, 'index'])->name('user-contacts');
