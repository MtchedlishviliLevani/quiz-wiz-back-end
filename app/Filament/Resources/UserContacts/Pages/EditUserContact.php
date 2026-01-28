<?php

namespace App\Filament\Resources\UserContacts\Pages;

use App\Filament\Resources\UserContacts\UserContactResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserContact extends EditRecord
{
    protected static string $resource = UserContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
