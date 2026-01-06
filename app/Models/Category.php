<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
	use HasApiTokens;

	protected $fillable = ['name'];

	public function quizzes(): BelongsToMany
	{
		return $this->belongsToMany(Quiz::class);
	}
}
