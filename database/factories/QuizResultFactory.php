<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizResult>
 */
class QuizResultFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$user = User::whereNotNull('email_verified_at')->inRandomOrder()->first();

		$quiz = Quiz::inRandomOrder()->first();

		if (!$user || !$quiz) {
			return [];
		}

		$quizMaxPoints = $quiz->questions()->sum('points');
		$score = $this->faker->numberBetween(0, $quizMaxPoints);

		$maxTime = $quiz->duration * 60;
		$timeSpent = $this->faker->numberBetween(30, $maxTime);

		return [
			'user_id'    => $user->id,
			'quiz_id'    => $quiz->id,
			'score'      => $score,
			'time_spent' => $timeSpent,
		];
	}
}
