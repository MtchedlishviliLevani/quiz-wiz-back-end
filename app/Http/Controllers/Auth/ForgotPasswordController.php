<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
	public function __invoke(ForgotPasswordRequest $request): JsonResponse
	{
		Password::sendResetLink($request->validated());

		return response()->json([
			'message' => 'If an account exists for that email address, a password reset link is on its way.',
		], 200);
	}
}
