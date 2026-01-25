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
			'answers'                => 'required|array',
			'answers.*.question_id'  => 'required|exists:questions,id',
			'answers.*.answer_ids'   => 'required|array',
			'answers.*.answer_ids.*' => 'exists:answers,id',
			'time_spent'             => 'required|integer', // time spent in seconds
		];
	}

	public function messages(): array
	{
		return [
			'answers.required'               => 'You must submit answers for the quiz.',
			'answers.*.question_id.required' => 'Question ID is required.',
			'answers.*.question_id.exists'   => 'Invalid question ID.',
			'answers.*.answer_ids.required'  => 'You must select at least one answer per question.',
			'answers.*.answer_ids.*.exists'  => 'Invalid answer ID.',
			'time_spent.required'            => 'Time spent is required.',
			'time_spent.integer'             => 'Time spent must be an integer in seconds.',
		];
	}
}
