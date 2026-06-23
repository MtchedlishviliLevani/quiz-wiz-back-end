<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
	public function __invoke(string $id, string $hash): JsonResponse
	{
		$user = User::findOrFail($id);

		if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
			return response()->json([
				'message' => 'Invalid or tampered verification link.',
			], 403);
		}

		if ($user->hasVerifiedEmail()) {
			return response()->json([
				'message' => 'Email already verified.',
			], 200);
		}

		$user->markEmailAsVerified();
		event(new Verified($user));

		return response()->json([
			'message' => 'Email verified successfully.',
		], 200);
	}
}
