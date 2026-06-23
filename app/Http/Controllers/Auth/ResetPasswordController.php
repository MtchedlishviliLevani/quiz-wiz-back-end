<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
	public function __construct(protected AuthService $authService)
	{
	}

	public function __invoke(ResetPasswordRequest $request): JsonResponse
	{
		$status = $this->authService->resetPassword($request->validated());

		if ($status === Password::PASSWORD_RESET) {
			return response()->json(['message' => 'Password reset successful.'], 200);
		}

		return response()->json(['message' => __($status)], 400);
	}
}
