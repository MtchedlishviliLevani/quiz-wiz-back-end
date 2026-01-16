<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

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
}
