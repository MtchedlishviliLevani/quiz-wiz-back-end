<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$baseCategories = [
			'History', 'Science', 'Math', 'Sports', 'Geography',
			'Literature', 'Art', 'Technology', 'Music', 'Movies',
			'Politics', 'Philosophy', 'Nature', 'Languages', 'Culture',
			'Health', 'Animals', 'Space', 'Economics', 'Education',
		];

		return [
			'name' => $this->faker->unique()->randomElement($baseCategories),
		];
	}
}
