<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserContact;
use Illuminate\Database\Seeder;

class UserContactSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$users = User::all();

		if ($users->isEmpty()) {
			$this->command->info('Seed users first.');
			return;
		}

		foreach ($users as $user) {
			UserContact::factory()
				->count(2)
				->for($user)
				->create();
		}
	}
}
