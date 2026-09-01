<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserContactController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(auth()->user()->contacts);
	}
}
