<?php

namespace App\Filament\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesTable
{
	public static function configure(Table $table): Table
	{
		return $table
			->columns([
				TextColumn::make('categories.name')
					->label('Categories')
					->badge()
					->searchable(),
				TextColumn::make('difficulty.level')
					->label('Difficulty (Level)')
					->sortable()
					->badge()
					->color(fn ($record) => $record->difficulty->color),
				TextColumn::make('difficulty.color')
					->label('Difficulty (Color)')
					->searchable(),
				TextColumn::make('title')
					->searchable(),
				ImageColumn::make('image'),
				TextColumn::make('duration')
					->numeric()
					->sortable(),
				TextColumn::make('created_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('updated_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
			])
			->recordActions([
				EditAction::make(),
			])
			->toolbarActions([
				BulkActionGroup::make([
					DeleteBulkAction::make(),
				]),
			]);
	}
}
