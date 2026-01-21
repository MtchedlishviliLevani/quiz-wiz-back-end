<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
	public function index(Request $request)
	{
		$user = auth()->user();

		$quizzes = Quiz::query()
			->with([
				'categories',
				'difficulty',
				'results' => fn ($q) => $user
					? $q->where('user_id', $user->id)
					->latest()
					->limit(1)
					: $q->whereNull('id'),
			])
			->withCount('results')
			->when(
				$request->boolean('my_quizzes'),
				fn ($q) => $q->myQuizzes($user)
			)
			->when($request->boolean('not_completed'), fn ($q) => $q->notCompleted($user))
			->when($request->filled('levels'), fn ($q) => $q->filterLevels($request->levels))
			->when($request->filled('categories'), fn ($q) => $q->filterCategories($request->categories))
			->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))
			->sortBy($request->get('sort_by'))
			->paginate(9);

		return QuizResource::collection($quizzes);
	}
}
