<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinCorrectAnswers implements ValidationRule
{
	protected int $min;

	public function __construct(int $min = 1)
	{
		$this->min = $min;
	}

	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$correctCount = collect($value)->where('is_correct', true)->count();

		if ($correctCount < $this->min) {
			$fail("At least {$this->min} correct answers are required.");
		}
	}
}
