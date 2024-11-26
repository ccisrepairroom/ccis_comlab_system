<?php

namespace App\Filament\Resources\StockUnitsResource\Pages;

use App\Filament\Resources\StockUnitsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockUnits extends EditRecord
{
    protected static string $resource = StockUnitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
