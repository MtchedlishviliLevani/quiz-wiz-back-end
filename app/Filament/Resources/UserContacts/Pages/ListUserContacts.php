<?php

namespace App\Filament\Resources\UserContacts\Pages;

use App\Filament\Resources\UserContacts\UserContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserContacts extends ListRecords
{
    protected static string $resource = UserContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
