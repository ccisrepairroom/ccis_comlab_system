<?php

namespace App\Filament\Resources\RequestListResource\Pages;

use App\Filament\Resources\RequestListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestList extends EditRecord
{
    protected static string $resource = RequestListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
