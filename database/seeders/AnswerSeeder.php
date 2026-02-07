<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$questions = Question::all();

		if ($questions->isEmpty()) {
			$this->command->info('Please seed questions first.');
			return;
		}

		foreach ($questions as $question) {
			Answer::factory()->count(4)->create([
				'question_id' => $question->id,
			]);
		}
	}
}
