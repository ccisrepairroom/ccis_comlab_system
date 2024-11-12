<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Filament\Resources\Pages\EditRecord;

class EditEquipment extends EditRecord
{
    protected static string $resource = EquipmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Edit ' . ($this->record->brand_name ?? 'Edit Equipment') . 
        ' (Serial No: ' . ($this->record->serial_no ?? 'N/A') . ')';
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }

   
}
