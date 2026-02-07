<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$quizzes = Quiz::all();

		if ($quizzes->isEmpty()) {
			$this->command->info('Please seed quizzes first.');
			return;
		}

		foreach ($quizzes as $quiz) {
			Question::factory()->count(4)->create([
				'quiz_id' => $quiz->id,
			]);
		}
	}
}
