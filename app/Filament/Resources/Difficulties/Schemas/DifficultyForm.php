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
					->required(),
				TextInput::make('color')
					->required()
					->label('Color (Hex Code)'),
			]);
	}
}
