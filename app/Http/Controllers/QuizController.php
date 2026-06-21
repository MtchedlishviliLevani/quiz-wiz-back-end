<?php

namespace App\Http\Controllers;

use App\Exceptions\QuizAlreadySubmittedException;
use App\Http\Requests\SubmitQuizRequest;
use App\Http\Resources\QuizQuestionsResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizShowResource;
use App\Http\Resources\QuizSubmitResource;
use App\Models\Quiz;
use App\Services\Quiz\QuizSubmissionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$user = auth()->user();

		$with = [
			'categories',
			'difficulty',
			'questions',
		];

		if ($user) {
			$with['results'] = fn (Relation $query) => $query->where('user_id', $user->id)
				->latest()
				->limit(1);
		}

		$quizzes = Quiz::query()
			->with($with)
			->withCount('results')
			->when(
				$request->boolean('my_quizzes'),
				fn (Builder $query) => $query->myQuizzes($user)
			)
			->when(
				$request->boolean('not_completed'),
				fn (Builder $query) => $query->notCompleted($user)
			)
			->when(
				$request->filled('levels'),
				fn (Builder $query) => $query->filterLevels($request->levels)
			)
			->when(
				$request->filled('categories'),
				fn (Builder $query) => $query->filterCategories($request->categories)
			)
			->when(
				$request->filled('search'),
				fn (Builder $query) => $query->where('title', 'like', '%' . $request->search . '%')
			)
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

	public function QuizQuestions(Quiz $quiz): QuizQuestionsResource
	{
		$quiz->load(['categories', 'questions.answers'])
			->loadCount(['questions', 'results']);

		return new QuizQuestionsResource($quiz);
	}


	/**
	 * @throws QuizAlreadySubmittedException
	 */
	public function submitQuiz(SubmitQuizRequest $request, QuizSubmissionService $service): QuizSubmitResource
	{
		$result = $service->submitQuiz(
			$request->quiz_id,
			$request->answers,
			$request->time_spent,
		);

		return new QuizSubmitResource($result);
	}
}
