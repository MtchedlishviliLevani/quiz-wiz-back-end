<?php

use App\Exceptions\QuizAlreadySubmittedException;
use App\Exceptions\RedirectEmailVerificationFailures;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware): void {
		$middleware->statefulApi();

		// There is no server-rendered login page; guests belong on the SPA.
		$middleware->redirectGuestsTo(fn (): string => config('app.frontend_url') . '/login');
	})
	->withExceptions(function (Exceptions $exceptions): void {
		$exceptions->render(new RedirectEmailVerificationFailures());

		$exceptions->render(function (InvalidSignatureException $e) {
			return response()->json([
				'message' => 'This verification link is invalid or has expired.',
			], 403);
		});

		$exceptions->render(function (QuizAlreadySubmittedException $e, $request) {
			return response()->json([
				'message' => 'You have already submitted this quiz.',
			], 403);
		});
	})->create()
;
