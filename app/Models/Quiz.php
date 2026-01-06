<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	protected $fillable = ['user_id', 'difficulty_id', 'title', 'description', 'image', 'duration'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function difficulty(): BelongsTo
	{
		return $this->belongsTo(Difficulty::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function questions(): HasMany
	{
		return $this->hasMany(Question::class);
	}

	public function results(): HasMany
	{
		return $this->hasMany(QuizResult::class);
	}
}
