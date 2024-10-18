<?php

namespace App\Filament\Resources\FacilitySummaryResource\Pages;

use App\Filament\Resources\FacilitySummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacilitySummary extends EditRecord
{
    protected static string $resource = FacilitySummaryResource::class;

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
