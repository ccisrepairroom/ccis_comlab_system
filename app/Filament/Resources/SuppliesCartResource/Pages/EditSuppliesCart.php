<?php

namespace App\Filament\Resources\SuppliesCartResource\Pages;

use App\Filament\Resources\SuppliesCartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuppliesCart extends EditRecord
{
    protected static string $resource = SuppliesCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
