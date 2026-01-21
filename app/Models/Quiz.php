<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	protected $fillable = ['user_id', 'difficulty_id', 'title', 'description', 'instructions', 'image', 'duration'];

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

	public function scopeMyQuizzes(Builder $query, ?User $user): Builder
	{
		if (!$user) {
			return $query;
		}
		return $query->whereHas('results', fn ($q) => $q->where('user_id', $user->id));
	}

	public function scopeNotCompleted(Builder $query, ?User $user): Builder
	{
		if (!$user) {
			return $query;
		}
		return $query->whereDoesntHave('results', fn ($q) => $q->where('user_id', $user->id));
	}

	public function scopeFilterLevels(Builder $query, array $levels): Builder
	{
		return $query->whereIn('difficulty_id', $levels);
	}

	public function scopeFilterCategories(Builder $query, array $categoryIds): Builder
	{
		return $query->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds));
	}

	public function scopeSearch(Builder $query, ?string $term): Builder
	{
		if (!$term) {
			return $query;
		}
		return $query->where('title', 'like', '%' . $term . '%');
	}

	public function scopeSortBy(Builder $query, ?string $sortBy): Builder
	{
		if (!$sortBy) {
			return $query;
		}

		switch ($sortBy) {
			case 'a-z':
				return $query->orderBy('title', 'asc');
			case 'z-a':
				return $query->orderBy('title', 'desc');
			case 'most_popular':
				return $query->withCount('results')->orderBy('results_count', 'desc');
			case 'newest':
				return $query->orderBy('created_at', 'desc');
			case 'oldest':
				return $query->orderBy('created_at', 'asc');
			default:
				return $query;
		}
	}
}
