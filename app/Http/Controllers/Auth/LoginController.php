<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        if (! $user) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
        ], 200);
    }
}
