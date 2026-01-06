<?php

use App\Models\Difficulty;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quizzes', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Difficulty::class)->constrained()->cascadeOnDelete();
			$table->string('title');
			$table->string('image');
			$table->text('description');
			$table->text('instructions');
			$table->integer('duration');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quizzes');
	}
};
