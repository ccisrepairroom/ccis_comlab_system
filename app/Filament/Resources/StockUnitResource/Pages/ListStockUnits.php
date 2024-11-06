<?php

namespace App\Filament\Resources\StockUnitResource\Pages;

use App\Filament\Resources\StockUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockUnits extends ListRecords
{
    protected static string $resource = StockUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("Create"),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
