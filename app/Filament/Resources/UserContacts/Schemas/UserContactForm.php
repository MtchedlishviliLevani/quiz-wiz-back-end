<?php

namespace App\Filament\Resources\UserContacts\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserContactForm
{
	public static function configure(Schema $schema): Schema
	{
		return $schema
			->components([
				Hidden::make('user_id')
					->default(fn (): int => auth()->id())->required(),
				TextInput::make('key')
					->required()
					->placeholder('e.g. Tel, Email, Facebook, Instagram, Linkedin'),
				TextInput::make('value')
					->required()->placeholder('e.g. +994501, https://facebook.com/user'),
			]);
	}
}
