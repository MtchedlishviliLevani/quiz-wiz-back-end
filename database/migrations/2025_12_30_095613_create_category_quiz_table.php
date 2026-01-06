<?php

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('category_quiz', function (Blueprint $table) {
			$table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Quiz::class)->constrained()->cascadeOnDelete();
			$table->unique(['category_id', 'quiz_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('category_quiz');
	}
};
