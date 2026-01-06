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
	public function register(array $data): User
	{
		$user = User::create([
			'username' => $data['username'],
			'email'    => $data['email'],
			'password' => Hash::make($data['password']),
		]);
		event(new Registered($user));

		Auth::login($user);

		return $user;
	}

	public function login(array $data): User|null
	{
		if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
			return null;
		}
		return Auth::user();
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
