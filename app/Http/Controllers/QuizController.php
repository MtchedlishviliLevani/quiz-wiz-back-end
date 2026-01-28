<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitQuizRequest;
use App\Http\Resources\QuizQuestionsResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizShowResource;
use App\Http\Resources\QuizSubmitResource;
use App\Models\Quiz;
use App\Services\Quiz\QuizSubmissionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
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
				fn (Builder $query): Builder => $query->myQuizzes($user)
			)
			->when($request->boolean('not_completed'), fn (Builder $query): Builder => $query->notCompleted($user))
			->when($request->filled('levels'), fn (Builder $query): Builder => $query->filterLevels($request->levels))
			->when($request->filled('categories'), fn (Builder $query): Builder => $query->filterCategories($request->categories))
			->when($request->filled('search'), fn (Builder $query): Builder => $query->where('title', 'like', '%' . $request->search . '%'))
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

	public function submitQuiz(SubmitQuizRequest $request, QuizSubmissionService $submissionService): QuizSubmitResource | JsonResponse
	{
		$quiz = Quiz::with([
			'questions.answers' => fn (Relation $q): Relation => $q->select('id', 'question_id', 'is_correct'),
		])->findOrFail($request->quiz_id);

		$timeSpent = $submissionService->clampTimeSpent($quiz, $request->time_spent);
		$evaluation = $submissionService->evaluate($quiz, $request->answers);

		if (auth()->check()) {
			$userId = auth()->id();
			$stored = $submissionService->storeResult(
				$quiz,
				$userId,
				$evaluation['totalPoints'],
				$timeSpent
			);

			if (!$stored) {
				return response()->json([
					'message' => 'You have already submitted this quiz.',
				], 403);
			}
		}

		return new QuizSubmitResource([
			'title'      => $quiz->title,
			'difficulty' => $quiz->difficulty ? [
				'level' => $quiz->difficulty->level,
				'color' => $quiz->difficulty->color,
			] : null,
			'time_spent' => $timeSpent,
			'correct'    => $evaluation['correctCount'],
			'mistakes'   => $evaluation['mistakes'],
		]);
	}
}
