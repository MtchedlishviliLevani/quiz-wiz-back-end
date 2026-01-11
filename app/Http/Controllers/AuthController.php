<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
	public function __construct(
		protected AuthService $authService
	) {
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
