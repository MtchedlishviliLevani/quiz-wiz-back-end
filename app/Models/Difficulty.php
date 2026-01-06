<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Difficulty extends Model
{
	protected $fillable = ['level', 'color'];

	public function quizzes(): HasMany
	{
		return  $this->hasMany(Quiz::class);
	}
}
