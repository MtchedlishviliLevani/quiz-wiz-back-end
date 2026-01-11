<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
}
