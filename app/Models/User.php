<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasName
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, HasApiTokens;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'username',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password'          => 'hashed',
		];
	}

	public function quizzes(): HasMany
	{
		return $this->hasMany(Quiz::class);
	}

	public function canAccessPanel(Panel $panel): bool
	{
		return $this->hasVerifiedEmail();
	}

	public function sendEmailVerificationNotification()
	{
		$this->notify(new VerifyEmailNotification());
	}

	public function sendPasswordResetNotification($token): void
	{
		$this->notify(new ResetPasswordNotification($token, $this->email));
	}

	public function results(): HasMany
	{
		return $this->hasMany(QuizResult::class);
	}

	public function getFilamentName(): string
	{
		return $this->username;
	}
}
