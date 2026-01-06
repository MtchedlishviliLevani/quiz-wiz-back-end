<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$user = auth('sanctum')->user();
		$userResult = null;

		if ($user && method_exists($this->resource, 'results')) {
			// We use the parentheses () to keep it as a Query Builder
			$userResult = $this->resource->results()
				->where('user_id', $user->id)
				->first();
		}

		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'description' => $this->description,
			'duration'    => $this->duration,
			// მოგვაქვს სირთულის სახელი და ფერი
			'difficulty' => [
				'name'  => $this->difficulty->level,
				'color' => $this->difficulty->color,
			],
			// მოგვაქვს კატეგორიების მხოლოდ სახელები (Array-ს სახით)
			'categories' => $this->categories->pluck('name'),
			'quiz_image' => $this->image,

			// სტატუსის შემოწმება
			'is_completed' => $userResult ? true : false,

			// ინფორმაცია მხოლოდ შევსებული ქვიზისთვის
			'user_data'    => $userResult ? [
				'score'      => $userResult->score ?? 0,
				'time_spent' => $userResult->time_spent ?? 0,
				// 2. Safe Date Check: only format if created_at exists
				'date'       => $userResult->created_at ? $userResult->created_at->format('Y-m-d') : null,
			] : null,
			'created_at' => $this->created_at->toFormattedDateString(),
		];
	}
}
