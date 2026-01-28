<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserContactController extends Controller
{
	public function index(): JsonResponse
	{
		if (!auth()->check()) {
			return response()->json(['message' => 'Unauthorized'], 401);
		}

		$contacts = auth()->user()->contacts;

		return response()->json($contacts);
	}
}
