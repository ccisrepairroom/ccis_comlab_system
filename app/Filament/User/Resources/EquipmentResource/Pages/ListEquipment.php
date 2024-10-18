<?php

namespace App\Filament\User\Resources\EquipmentResource\Pages;

use App\Filament\User\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
