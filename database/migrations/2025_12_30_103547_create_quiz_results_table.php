<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quiz_results', function (Blueprint $table) {
			$table->id();

			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('quiz_id')->constrained()->cascadeOnDelete();

			$table->integer('score');           // მომხმარებლის ქულა (მაგ: 8)
			$table->integer('total_points');    // მაქსიმალური ქულა (მაგ: 10)
			$table->integer('time_spent');      // დახარჯული დრო წამებში (მაგ: 120)
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quiz_results');
	}
};
