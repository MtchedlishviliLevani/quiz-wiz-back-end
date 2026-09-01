<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
	public function __construct(protected AuthService $authService)
	{
	}

	public function __invoke(RegisterRequest $request): JsonResponse
	{
		$user = $this->authService->register($request->validated());

		return response()->json([
			'message' => 'User registered successfully',
			'user'    => new UserResource($user),
		], 201);
	}
}
