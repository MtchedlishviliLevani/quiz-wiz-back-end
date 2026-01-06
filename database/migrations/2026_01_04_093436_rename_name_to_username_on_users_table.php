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
		Schema::table('users', function (Blueprint $table) {
			// 1. ვუცვლით სახელს name -> username
			$table->renameColumn('name', 'username');
		});

		Schema::table('users', function (Blueprint $table) {
			// 2. ვხდით Unique-ს (ცალკე ხაზზე იმიტომაა, რომ ზოგიერთ ბაზაში ერთად ვერ აკეთებს)
			$table->unique('username');
		});
	}

	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropUnique(['username']);
			$table->renameColumn('username', 'name');
		});
	}
};
