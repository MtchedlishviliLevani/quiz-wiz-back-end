<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
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
			'totalPoints' => $totalPoints,
			'correctCount' => $correctCount,
			'mistakes' => $mistakes,
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
				'user_id' => $userId,
				'score' => $score,
				'time_spent' => $timeSpent,
			]);
		});

		return true;
	}
}

