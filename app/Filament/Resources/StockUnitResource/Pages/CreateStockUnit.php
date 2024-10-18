<?php

namespace App\Filament\Resources\StockUnitResource\Pages;

use App\Filament\Resources\StockUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockUnit extends CreateRecord
{
    protected static string $resource = StockUnitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
