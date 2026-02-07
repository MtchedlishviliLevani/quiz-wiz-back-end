<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$users = User::all();
		$difficulties = Difficulty::all();
		$categories = Category::pluck('id');

		if ($users->isEmpty() || $difficulties->isEmpty() || $categories->isEmpty()) {
			$this->command->info('Seed users, difficulties, and categories first.');
			return;
		}

		Quiz::factory()->count(10)->make()->each(function ($quiz) use ($users, $difficulties, $categories) {
			$quiz->user_id = $users->random()->id;
			$quiz->difficulty_id = $difficulties->random()->id;
			$quiz->save();

			$quiz->categories()->attach(
				$categories->random(rand(1, 3))->toArray()
			);
		});
	}
}
