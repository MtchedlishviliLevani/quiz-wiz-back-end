<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	use HasFactory;

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
		return $query->whereHas('results', fn (Builder $resultsQuery) => $resultsQuery->where('user_id', $user->id));
	}

	public function scopeNotCompleted(Builder $query, ?User $user): Builder
	{
		if (!$user) {
			return $query;
		}
		return $query->whereDoesntHave('results', fn (Builder $resultsQuery) => $resultsQuery->where('user_id', $user->id));
	}

	public function scopeFilterLevels(Builder $query, array $levels): Builder
	{
		return $query->whereIn('difficulty_id', $levels);
	}

	public function scopeFilterCategories(Builder $query, array $categoryIds): Builder
	{
		return $query->whereHas('categories', fn (Builder $categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds));
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

		return match ($sortBy) {
			'a-z'          => $query->orderBy('title', 'asc'),
			'z-a'          => $query->orderBy('title', 'desc'),
			'most_popular' => $query->withCount('results')->orderBy('results_count', 'desc'),
			'newest'       => $query->orderBy('created_at', 'desc'),
			'oldest'       => $query->orderBy('created_at', 'asc'),
			default        => $query,
		};
	}
}
