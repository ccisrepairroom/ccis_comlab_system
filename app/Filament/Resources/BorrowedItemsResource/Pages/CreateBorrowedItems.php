<?php

namespace App\Filament\Resources\BorrowedItemsResource\Pages;

use App\Filament\Resources\BorrowedItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowedItems extends CreateRecord
{
    protected static string $resource = BorrowedItemsResource::class;
}
