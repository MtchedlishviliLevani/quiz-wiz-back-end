<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizSubmitResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'title'      => $this->resource['quiz']->title,
			'difficulty' => $this->resource['quiz']->difficulty ? [
				'level' => $this->resource['quiz']->difficulty->level,
				'color' => $this->resource['quiz']->difficulty->color,
			] : null,
			'time_spent' => $this->resource['time_spent'],
			'correct'    => $this->resource['evaluation']['correctCount'],
			'mistakes'   => $this->resource['evaluation']['mistakes'],
		];
	}
}
