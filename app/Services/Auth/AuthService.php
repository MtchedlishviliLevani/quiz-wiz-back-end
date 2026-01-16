<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class AuthService
{
	public function register(array $credentials): User
	{
		$user = User::create($credentials);

		event(new Registered($user));

		Auth::login($user);

		return $user;
	}

	public function login(array $credentials): ?User
	{
		$remember = $credentials['remember_me'] ?? false;
		unset($credentials['remember_me']);

		if (!Auth::guard('web')->attempt($credentials, $remember)) {
			return null;
		}

		session()->regenerate();

		return Auth::guard('web')->user();
	}
}
