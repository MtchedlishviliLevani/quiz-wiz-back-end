<?php

namespace App\Services\Quiz;

use App\Exceptions\QuizAlreadySubmittedException;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class QuizSubmissionService
{
	public function clampTimeSpent(Quiz $quiz, int $timeSpent): int
	{
		return min($timeSpent, $quiz->duration * 60);
	}

	public function evaluate(Quiz $quiz, array $answers): array
	{
		$submittedAnswers = collect($answers);
		$totalPoints = 0;
		$correctCount = 0;

		foreach ($quiz->questions as $question) {
			$submitted = $submittedAnswers->firstWhere('question_id', $question->id);

			if (!$submitted) {
				continue;
			}

			$submittedIds = collect($submitted['answer_ids'])->sort()->values();
			$correctIds = $question->answers
				->where('is_correct', true)
				->pluck('id')
				->sort()
				->values();

			if ($submittedIds->all() === $correctIds->all()) {
				$correctCount++;
				$totalPoints += $question->points;
			}
		}

		$mistakes = $quiz->questions->count() - $correctCount;

		return [
			'totalPoints'  => $totalPoints,
			'correctCount' => $correctCount,
			'mistakes'     => $mistakes,
		];
	}

	public function storeResult(Quiz $quiz, int $userId, int $score, int $timeSpent): bool
	{
		$existingResult = $quiz->results()->where('user_id', $userId)->first();

		if ($existingResult) {
			return false;
		}

		DB::transaction(function () use ($quiz, $userId, $score, $timeSpent) {
			$quiz->results()->create([
				'user_id'    => $userId,
				'score'      => $score,
				'time_spent' => $timeSpent,
			]);
		});

		return true;
	}

	/**
	 * @throws QuizAlreadySubmittedException
	 */
	public function submitQuiz(int $quizId, array $answers, int $timeSpent): array
	{
		$quiz = Quiz::with([
			'questions.answers' => fn (Relation $q): Relation => $q->select('id', 'question_id', 'is_correct'),
		])->findOrFail($quizId);

		$timeSpent = $this->clampTimeSpent($quiz, $timeSpent);
		$evaluation = $this->evaluate($quiz, $answers);

		$user = auth()->user();

		if ($user && $user->hasVerifiedEmail()) {
			$stored = $this->storeResult(
				$quiz,
				auth()->id(),
				$evaluation['totalPoints'],
				$timeSpent
			);

			if (!$stored) {
				throw new QuizAlreadySubmittedException('You have already submitted this quiz.');
			}
		}

		return [
			'quiz'       => $quiz,
			'evaluation' => $evaluation,
			'time_spent' => $timeSpent,
		];
	}
}
