<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizQuestionsResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                      => $this->id,
			'quiz_title'              => $this->title,
			'categories'              => $this->categories->map->only(['id', 'name']),

			'questions_count'    => $this->questions_count,

			'max_points'                  => (int) $this->questions->sum('points'),
			'play_count'                  => $this->results_count,
			'quiz_duration'               => $this->duration,
			'questions'                   => $this->questions->map(fn ($question, $index): array => [
				'id'            => $question->id,
				'question'      => $question->question,
				'number'        => $index + 1,
				'correct_count' => $question->answers->where('is_correct', true)->count(),
				'answers'       => $question->answers->map(fn ($answer): array => [
					'id'         => $answer->id,
					'text'       => $answer->text,
				]),
			]),
		];
	}
}
