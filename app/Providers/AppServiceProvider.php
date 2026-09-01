<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void {}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		$this->configurePasswordDefaults();
		$this->configureRateLimiting();
	}

	protected function configurePasswordDefaults(): void
	{
		Password::defaults(fn(): Password => Password::min(8)->letters()->numbers());
	}

	protected function configureRateLimiting(): void
	{
		RateLimiter::for('register', fn(Request $request): Limit => Limit::perMinute(5)->by($request->ip()));

		RateLimiter::for('password-reset', fn(Request $request): array => [
			Limit::perMinute(5)->by($request->ip()),
			Limit::perMinutes(60, 3)->by($this->emailKey($request)),
		]);
	}

	protected function emailKey(Request $request): string
	{
		return 'email:' . Str::lower((string) $request->input('email'));
	}
}
