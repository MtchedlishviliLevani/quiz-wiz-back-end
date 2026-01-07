<?php

namespace App\Filament\Resources\Difficulties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DifficultyForm
{
	public static function configure(Schema $schema): Schema
	{
		return $schema
			->components([
				TextInput::make('level')
					->required()
					->unique(),
				TextInput::make('color')
					->required()
					->unique(),
			]);
	}
}
