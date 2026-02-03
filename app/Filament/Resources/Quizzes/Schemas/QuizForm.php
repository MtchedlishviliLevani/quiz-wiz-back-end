<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Models\Difficulty;
use App\Rules\MinCorrectAnswers;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as ComponentsSection;

class QuizForm
{
	public static function configure(Schema $schema): Schema
	{
		return $schema
			->components([
				Hidden::make('user_id')
					->default(fn () => auth()->id()),
				Select::make('categories')
					->relationship('categories', 'name')
					->multiple()
					->preload()
					->required(),
				Select::make('difficulty_id')
					->relationship('difficulty', 'level')
					 ->getOptionLabelFromRecordUsing(
					 	fn (Difficulty $record): string => "{$record->level} ({$record->color})"
					 )
					->required()
					->native(false),
				TextInput::make('title')
					->maxLength(255)
					->required(),
				FileUpload::make('image')->disk('public')
					->image()
					->directory('quizzes')
					->preserveFilenames(false)
					->maxSize(2048)
					->required()
					->validationMessages([
						'required' => 'Image upload is required.',
						'image'    => 'Please upload a valid image file.',
						'max'      => 'The image size must not exceed 2 MB.',
					]),
				Textarea::make('description')
					->required()
					->minLength(20)
					->columnSpanFull(),
				Textarea::make('instructions')
					->required()
					->minLength(20)
					->columnSpanFull(),
				TextInput::make('duration')
					->label('Duration (Minutes)')
					->required()
					->numeric(),
				ComponentsSection::make('Questions & Answers')
				->description('Manage quiz questions and their multiple choice answers here.')
				->schema([
					Repeater::make('questions')
						->relationship('questions')
						->label('Questions')
						->schema([
							TextInput::make('question')
								->label('Question Text')
								->required()
								->columnSpanFull(),

							TextInput::make('points')
								->numeric()
								->default(1)
								->helperText('Points for a correct answer'),

							Repeater::make('answers')
								->relationship('answers')
								->rule([new MinCorrectAnswers(1)])
								->label('Answers')
								->schema([
									TextInput::make('text')
										->label('Answer Text')
										->required(),

									Toggle::make('is_correct')
										->label('Is Correct?')
										->onColor('success')
										->offColor('danger'),
								])
								->grid(2)
								->minItems(2)
								->addActionLabel('Add Answer')
								->collapsible(),
						])
						->itemLabel(fn (array $state): ?string => $state['text'] ?? 'New Question')
						->collapsible()
						->addActionLabel('Add New Question'),
				]),
			]);
	}
}
