<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		$userResult = $this->results->first();

		return [
			'id' => $this->id,

			'is_completed' => (bool) $userResult,

			'image' => $this->image,
			'title' => $this->title,

			'categories' => $this->categories->map->only(['id', 'name']),

			'difficulty' => $this->difficulty ? [
				'level' => $this->difficulty->level,
				'color' => $this->difficulty->color,
			] : null,

			'total_users' => $this->results_count,

			'total_time' => $userResult?->time_spent ?? $this->total_time,

			'completed' => $userResult ? [
				'completed_at' => $userResult->created_at,
				'user_score'   => $userResult->score,
				'total_points' => $userResult->total_points,
			] : null,
		];
	}
}
