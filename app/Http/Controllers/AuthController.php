<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Auth\Events\Verified;
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

	public function verify($id, $hash): JsonResponse
	{
		$user = User::findOrFail($id);
		if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
			return response()->json(['message' => 'Invalid verification link'], 403);
		}

		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();
			event(new Verified($user));
		}
		return response()->json(['message' => 'Email verified']);
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

	public function resetPassword(ResetPasswordRequest $request): JsonResponse
	{
		$status = $this->authService->resetPassword($request->validated());

		if ($status === Password::PASSWORD_RESET) {
			return response()->json(['message' => 'Password reset successful.'], 200);
		}

		return response()->json(['message' => __($status)], 400);
	}
}
