<?php

namespace App\Filament\Resources\BorrowListResource\Pages;

use App\Filament\Resources\BorrowListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowList extends EditRecord
{
    protected static string $resource = BorrowListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
