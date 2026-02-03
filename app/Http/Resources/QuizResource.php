<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QuizResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		$userResult = auth()->check()
	? $this->results->firstWhere('user_id', auth()->id())
	: null;

		$maxScore = (int) $this->questions()->sum('points');

		return [
			'id' => $this->id,

			'is_completed' => (bool) $userResult,

			'image' => Storage::disk('public')->url($this->image),

			'title' => $this->title,

			'categories' => $this->categories->map->only(['id', 'name']),

			'difficulty' => $this->difficulty ? [
				'level' => $this->difficulty->level,
				'color' => $this->difficulty->color,
			] : null,

			'total_users' => $this->results_count,

			'completed' => $userResult ? [
				'completed_at' => $userResult->created_at,
				'user_score'   => $userResult->score,
				'max_score'    => $maxScore,
				'total_time'   => $userResult?->time_spent,
			] : null,
		];
	}
}
