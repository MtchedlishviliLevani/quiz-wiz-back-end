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
}
