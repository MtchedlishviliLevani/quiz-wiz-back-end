<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;

class EmailVerificationController extends Controller
{
	public function __invoke(EmailVerificationRequest $request): RedirectResponse
	{
		$request->fulfill();

		return redirect()->away(self::redirectUrl('verified'));
	}

	public static function redirectUrl(string $status): string
	{
		return Config::get('app.frontend_url') . '/verify-email?' . http_build_query(['status' => $status]);
	}
}
