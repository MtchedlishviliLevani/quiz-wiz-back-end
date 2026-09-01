<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
	public function __construct(
		protected AuthService $authService
	) {
	}

	public function __invoke(LoginRequest $request): JsonResponse
	{
		$user = $this->authService->login($request->validated());

		if (!$user) {
			return response()->json([
				'message' => 'Invalid email or password.',
			], 401);
		}

		$request->session()->regenerate();

		return response()->json([
			'user' => new UserResource($user),
		], 200);
	}
}
