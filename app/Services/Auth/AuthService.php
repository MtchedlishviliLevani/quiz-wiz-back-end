<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
	public function register(array $credentials): User
	{
		$user = User::create($credentials);

		event(new Registered($user));

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

	public function resetPassword(array $credentials): string
	{
		return Password::reset(
			$credentials,
			function (User $user, string $password) {
				$user->forceFill([
					'password'       => Hash::make($password),
					'remember_token' => Str::random(60),
				])->save();

				event(new PasswordReset($user));
			}
		);
	}
}
