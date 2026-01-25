<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizShowResource extends JsonResource
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
			'title'                   => $this->title,
			'image'                   => $this->image,
			'description'             => $this->description,
			'instructions'            => $this->instructions,
			'categories'              => $this->categories->map->only(['id', 'name']),
			'quiz_duration'           => $this->duration,
			'max_score'               => (int) $this->questions()->sum('points'),
			'question_amount'         => $this->questions->count(),
			'play_count'              => $this->results_count,
			'similar_quizzes'         => isset($this->similarQuizzes)
				? QuizResource::collection($this->similarQuizzes)
				: [],
		];
	}
}
