<?php

namespace App\Filament\Resources\EquipmentSummaryResource\Pages;

use App\Filament\Resources\EquipmentSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\EquipmentMonitoring;

class EditEquipmentSummary extends EditRecord
{
    protected static string $resource = EquipmentSummaryResource::class;

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
