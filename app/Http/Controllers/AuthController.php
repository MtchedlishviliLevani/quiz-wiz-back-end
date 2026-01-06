<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Password as PasswordFacade;

class AuthController extends Controller
{
	public function __construct(
		protected AuthService $authService
	) {
	}

	// public function login(LoginRequest $request): JsonResponse
	// {
	// 	$user = $this->authService->login($request->all());

	// 	if (!$user) {
	// 		return response()->json([
	// 			'message' => 'Invalid email or password.',
	// 		], 401);
	// 	}

	// 	$request->session()->regenerate();

	// 	return response()->json([
	// 		'message' => 'Authorization successful!',
	// 		'user'    => [
	// 			'id'       => $user->id,
	// 			'username' => $user->username,
	// 			'email'    => $user->email,
	// 		],
	// 	], 200);
	// }

	public function login(LoginRequest $request): JsonResponse
	{
		$user = $this->authService->login($request->validated());

		if (!$user) {
			return response()->json([
				'message' => 'Invalid email or password.',
			], 401);
		}

		$request->session()->regenerate();

		return response()->json([
			'message' => 'Authorization successful!',
			'user'    => [
				'id'       => $user->id,
				'username' => $user->username,
				'email'    => $user->email,
			],
		], 200);
	}

	public function logout(Request $request): JsonResponse
	{
		/** @var \App\Models\User $user */
		$user = $request->user();
		$user->currentAccessToken()->delete();

		return response()->json([
			'message' => 'Successfully logged out.',
		]);
	}

	public function verify(EmailVerificationRequest $request): JsonResponse
	{
		try {
			// 1. თუ უკვე ვერიფიცირებულია
			if ($request->user()->hasVerifiedEmail()) {
				return response()->json(['message' => 'Email already verified.'], 200);
			}

			// 2. ვერიფიკაციის პროცესი
			if ($request->user()->markEmailAsVerified()) {
				event(new \Illuminate\Auth\Events\Verified($request->user()));
			}

			return response()->json([
				'message'  => 'Email verified successfully.',
				'verified' => true,
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'Verification failed',
				'error'   => $e->getMessage(),
			], 500);
		}
	}

	// public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
	// {
	// 	$this->authService->sendResetLink($request->validated());

	// 	return response()->json([
	// 		'message' => 'Password reset link sent to your email address.',
	// 	]);
	// }

	public function forgotPassword(Request $request): JsonResponse
	{
		// 1. ვალიდაცია
		$request->validate(['email' => 'required|email']);

		// 2. პაროლის აღდგენის ლინკის გაგზავნა
		// Password::broker() პოულობს იუზერს და უგზავნის იმეილს
		$status = Password::broker()->sendResetLink(
			$request->only('email')
		);

		// 3. პასუხი სტატუსის მიხედვით
		// RESET_LINK_SENT ნიშნავს რომ ყველაფერმა კარგად ჩაიარა
		if ($status === Password::RESET_LINK_SENT) {
			return response()->json([
				'message' => __($status), // "We have emailed your password reset link!"
			], 200);
		}

		// თუ იუზერი ვერ მოიძებნა ან სხვა შეცდომაა
		return response()->json([
			'message' => __($status),
		], 422);
	}

	// public function resetPassword(ResetPasswordRequest $request): JsonResponse
	// {
	// 	$status = $this->authService->resetPassword($request->validated());

	// 	if ($status === Password::PASSWORD_RESET) {
	// 		return response()->json([
	// 			'message' => 'Password has been reset successfully.',
	// 		]);
	// 	}

	// 	return response()->json([
	// 		'message' => 'Token is invalid or expired.',
	// 	], 400);
	// }

	// ...

	public function resetPassword(Request $request): JsonResponse
	{
		$status = PasswordFacade::reset(
			$request->validated(),
			function ($user) use ($request) {
				$user->forceFill([
					'password' => Hash::make($request->password),
				])->save();
			}
		);

		if ($status === PasswordFacade::PASSWORD_RESET) {
			return response()->json(['message' => 'Password reset successful.'], 200);
		}

		return response()->json(['message' => __($status)], 400);
	}

	public function register(RegisterRequest $request)
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
}
