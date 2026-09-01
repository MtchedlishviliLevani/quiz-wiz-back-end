<?php

namespace App\Exceptions;

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

final class RedirectEmailVerificationFailures
{
	public function __invoke(Throwable $e, Request $request): ?RedirectResponse
	{
		if (!$request->routeIs('verification.verify')) {
			return null;
		}

		$status = $this->statusFor($e);

		return $status === null
			? null
			: redirect()->away(EmailVerificationController::redirectUrl($status));
	}

	public function statusFor(Throwable $e): ?string
	{
		// The handler runs prepareException() before render callbacks, so an
		// AuthorizationException arrives here as AccessDeniedHttpException.
		return match (true) {
			$e instanceof InvalidSignatureException => 'expired',
			$e instanceof AuthenticationException   => 'unauthenticated',
			$e instanceof ThrottleRequestsException => 'throttled',
			$e instanceof AuthorizationException,
			$e instanceof AccessDeniedHttpException => 'invalid',
			default                                 => null,
		};
	}
}
