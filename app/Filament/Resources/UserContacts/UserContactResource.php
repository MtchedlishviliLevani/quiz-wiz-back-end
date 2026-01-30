<?php

namespace App\Filament\Resources\UserContacts;

use App\Filament\Resources\UserContacts\Pages\CreateUserContact;
use App\Filament\Resources\UserContacts\Pages\EditUserContact;
use App\Filament\Resources\UserContacts\Pages\ListUserContacts;
use App\Filament\Resources\UserContacts\Schemas\UserContactForm;
use App\Filament\Resources\UserContacts\Tables\UserContactsTable;
use App\Models\UserContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserContactResource extends Resource
{
	protected static ?string $model = UserContact::class;

	protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

	public static function form(Schema $schema): Schema
	{
		return UserContactForm::configure($schema);
	}

	public static function table(Table $table): Table
	{
		return UserContactsTable::configure($table);
	}

	public static function getPages(): array
	{
		return [
			'index'  => ListUserContacts::route('/'),
			'create' => CreateUserContact::route('/create'),
			'edit'   => EditUserContact::route('/{record}/edit'),
		];
	}

	public static function getEloquentQuery(): Builder
	{
		return parent::getEloquentQuery()->where('user_id', auth()->id());
	}
}
