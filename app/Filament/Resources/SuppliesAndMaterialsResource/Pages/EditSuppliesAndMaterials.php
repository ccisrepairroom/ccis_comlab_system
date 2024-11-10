<?php

namespace App\Filament\Resources\SuppliesAndMaterialsResource\Pages;

use App\Filament\Resources\SuppliesAndMaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuppliesAndMaterials extends EditRecord
{
    protected static string $resource = SuppliesAndMaterialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return 'Edit ' . ($this->record->item ?? 'Edit Supplies and Materials'); 
        
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
