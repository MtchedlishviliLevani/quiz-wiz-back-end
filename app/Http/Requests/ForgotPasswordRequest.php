<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'email' => 'required|email|exists:users,email',
		];
	}

	public function messages(): array
	{
		return [
			'email.exists'   => 'A user with this email address does not exist.',
			'email.required' => 'The email field is required.',
		];
	}
}
