<?php

namespace App\Filament\Resources\SuppliesCartResource\Pages;

use App\Filament\Resources\SuppliesCartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuppliesCarts extends ListRecords
{
    protected static string $resource = SuppliesCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
