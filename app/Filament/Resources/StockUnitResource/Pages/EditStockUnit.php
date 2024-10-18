<?php

namespace App\Filament\Resources\StockUnitResource\Pages;

use App\Filament\Resources\StockUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockUnit extends EditRecord
{
    protected static string $resource = StockUnitResource::class;

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
