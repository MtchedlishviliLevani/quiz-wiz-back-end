<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$user = auth()->user();

		$quizzes = Quiz::query()
			->with([
				'categories',
				'difficulty',
				'results' => fn (Builder $resultsQuery) => $user
					? $resultsQuery->where('user_id', $user->id)
						->latest()
						->limit(1)
					: $resultsQuery->whereNull('id'),
			])
			->withCount('results')
			->when(
				$request->boolean('my_quizzes'),
				fn (Builder $q) => $q->myQuizzes($user)
			)
			->when($request->boolean('not_completed'), fn (Builder $query) => $query->notCompleted($user))
			->when($request->filled('levels'), fn (Builder $q) => $q->filterLevels($request->levels))
			->when($request->filled('categories'), fn (Builder $q) => $q->filterCategories($request->categories))
			->when($request->filled('search'), fn (Builder $q) => $q->where('title', 'like', '%' . $request->search . '%'))
			->sortBy($request->get('sort_by'))
			->paginate(9);

		return QuizResource::collection($quizzes);
	}
}
