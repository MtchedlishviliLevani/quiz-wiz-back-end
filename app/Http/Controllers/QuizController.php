<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): AnonymousResourceCollection
	{
		if ($token = request()->bearerToken()) {
			auth()->shouldUse('sanctum');
		}
		$quizzes = Quiz::with(['difficulty', 'categories'])
			->withCount('questions')
			->oldest()
			->get();

		return QuizResource::collection($quizzes);
	}
}
