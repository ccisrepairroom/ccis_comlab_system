<?php

namespace App\Filament\User\Resources\BorrowListResource\Pages;

use App\Filament\User\Resources\BorrowListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBorrowLists extends ListRecords
{
    protected static string $resource = BorrowListResource::class;

    protected static ?string $title = 'Requests';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
