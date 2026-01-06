<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
	public function register(array $credentials): User
	{
		$user = User::create([
			'username' => $credentials['username'],
			'email'    => $credentials['email'],
			'password' => Hash::make($credentials['password']),
		]);
		event(new Registered($user));

		Auth::login($user);

		return $user;
	}

	public function login(array $credentials): ?User
	{
		if (!Auth::attempt($credentials)) {
			return null;
		}

		$user = Auth::user();

		return $user;
	}

	public function sendResetLink(array $data)
	{
		$user = User::where('email', $data['email'])->first();

		if (!$user) {
			return false;
		}

		$token = Password::createToken($user);

		$user->notify(new ResetPasswordNotification($token, $user->email));

		return true;
	}

	public function resetPassword(array $data)
	{
		return Password::reset(
			$data,
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->save();
			}
		);
	}
}
