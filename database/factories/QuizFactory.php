<?php

namespace Database\Factories;

use App\Models\Difficulty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title'         => $this->faker->sentence(3),
			'description'   => $this->faker->paragraph(),
			'instructions'  => $this->faker->paragraph(),
			'duration'      => $this->faker->numberBetween(5, 60),
			'image'         => 'https://picsum.photos/800/600?random=' . $this->faker->unique()->numberBetween(1, 1000),
			'user_id'       => User::inRandomOrder()->first()->id,
			'difficulty_id' => Difficulty::inRandomOrder()->first()->id,
		];
	}
}
