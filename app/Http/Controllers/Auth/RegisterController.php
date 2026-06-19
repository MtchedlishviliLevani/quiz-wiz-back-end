<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        $request->session()->regenerate();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ], 201);
    }
}
