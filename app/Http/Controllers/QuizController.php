<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizShowResource;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
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
				'questions',
				'results' => fn (Relation $resultsQuery) => $user
					? $resultsQuery->where('user_id', $user->id)
						->latest()
						->limit(1)
					: $resultsQuery->whereNull('id'),
			])
			->withCount('results')
			->when(
				$request->boolean('my_quizzes'),
				fn (Builder $query) => $query->myQuizzes($user)
			)
			->when($request->boolean('not_completed'), fn (Builder $query) => $query->notCompleted($user))
			->when($request->filled('levels'), fn (Builder $query) => $query->filterLevels($request->levels))
			->when($request->filled('categories'), fn (Builder $query) => $query->filterCategories($request->categories))
			->when($request->filled('search'), fn (Builder $query) => $query->where('title', 'like', '%' . $request->search . '%'))
			->sortBy($request->get('sort_by'))
			->paginate(9);

		return QuizResource::collection($quizzes);
	}

	public function show(Quiz $quiz): QuizShowResource
	{
		$quiz->load([
			'categories',
			'difficulty',
			'questions',
		])->loadCount('questions')
		  ->loadCount('results');

		$quiz->similarQuizzes = Quiz::query()
			->with(['categories'])
			->withCount('results')
			->where('id', '!=', $quiz->id)

			->whereHas('categories', function (Builder $q) use ($quiz) {
				$q->whereIn('categories.id', $quiz->categories->pluck('id'));
			})

			->when(auth()->check(), function (Builder $query) {
				$query->whereDoesntHave('results', function (Builder $q) {
					$q->where('user_id', auth()->id());
				});
			})

			->limit(3)
			->get();

		return new QuizShowResource($quiz);
	}
}
