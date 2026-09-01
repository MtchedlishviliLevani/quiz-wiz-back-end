<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendVerificationEmailController extends Controller
{
	public function __invoke(Request $request): JsonResponse
	{
		if ($request->user()->hasVerifiedEmail()) {
			return response()->json([
				'message' => 'Your email address is already verified.',
			], 200);
		}

		$request->user()->sendEmailVerificationNotification();

		return response()->json([
			'message' => 'A new verification link has been sent to your email address.',
		], 202);
	}
}
