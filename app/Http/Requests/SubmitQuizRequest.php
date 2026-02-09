<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'answers'                => 'array',
			'answers.*.question_id'  => 'sometimes|exists:questions,id',
			'answers.*.answer_ids'   => 'sometimes|array',
			'answers.*.answer_ids.*' => 'exists:answers,id',
			'time_spent'             => 'required|integer',
		];
	}
}
