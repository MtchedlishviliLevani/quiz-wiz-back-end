<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
	public function __construct(
		protected AuthService $authService
	) {
	}

	public function register(RegisterRequest $request): JsonResponse
	{
		$user = $this->authService->register($request->validated());

		$request->session()->regenerate();

		return response()->json([
			'message' => 'User registered successfully',
			'user'    => [
				'id'       => $user->id,
				'username' => $user->username,
				'email'    => $user->email,
			],
		], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$user = $this->authService->login($request->validated());

		if (!$user) {
			return response()->json([
				'message' => 'Invalid email or password.',
			], 401);
		}

		return response()->json([
			'message' => 'Authorization successful!',
			'user'    => [
				'id'       => $user->id,
				'username' => $user->username,
				'email'    => $user->email,
			],
		], 200);
	}

	public function verify(EmailVerificationRequest $request): JsonResponse
	{
		$request->fulfill();
		return response()->json(['message' => 'Email verified.']);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return response()->json([
			'message' => 'You’ve been logged out successfully.',
		]);
	}

	public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
	{
		$status = Password::sendResetLink(
			$request->validated()
		);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json([
				'message' => __($status),
			], 200);
		}

		return response()->json([
			'message' => __($status),
		], 422);
	}
}
