<?php

namespace App\Filament\Resources\Quizzes\Tables;

use App\Models\Quiz;
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
					->color(fn (Quiz $record): string => $record->difficulty->color),
				TextColumn::make('difficulty.color')
					->label('Difficulty (Color)')
					->searchable(),
				TextColumn::make('title')
					->searchable(),
				ImageColumn::make('image')->disk('public'),
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
