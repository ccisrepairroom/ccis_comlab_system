<?php

namespace App\Filament\Resources\BorrowedItemsResource\Pages;

use App\Filament\Resources\BorrowedItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowedItems extends EditRecord
{
    protected static string $resource = BorrowedItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
