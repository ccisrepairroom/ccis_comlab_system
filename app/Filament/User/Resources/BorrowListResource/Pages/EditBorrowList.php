<?php

namespace App\Filament\User\Resources\BorrowListResource\Pages;

use App\Filament\User\Resources\BorrowListResource;
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
}
