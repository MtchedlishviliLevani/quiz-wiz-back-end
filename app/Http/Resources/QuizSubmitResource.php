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
			'title'       => $this['title'],
			'difficulty'  => $this['difficulty'],
			'time_spent'  => $this['time_spent'],
			'correct'     => $this['correct'],
			'mistakes'    => $this['mistakes'],
		];
	}
}
