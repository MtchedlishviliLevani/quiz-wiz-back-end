<?php

namespace Database\Seeders;

use App\Models\Difficulty;
use Illuminate\Database\Seeder;

class DifficultySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$difficulties = [
			['level' => 'Beginner', 'color' => '#6CC551'],
			['level' => 'Challenger', 'color' => '#FFD166'],
			['level' => 'Intermediate', 'color' => '#4EA8DE'],
			['level' => 'Advanced', 'color' => '#FF6B6B'],
			['level' => 'Expert', 'color' => '#9B5DE5'],
			['level' => 'Master', 'color' => '#FF3C38'],
		];

		foreach ($difficulties as $difficulty) {
			Difficulty::create($difficulty);
		}
	}
}
