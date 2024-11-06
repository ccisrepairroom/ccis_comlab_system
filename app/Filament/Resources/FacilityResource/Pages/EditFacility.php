<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacility extends EditRecord
{
    protected static string $resource = FacilityResource::class;

    /*protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }*/

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
<<<<<<< HEAD
=======
    public function getTitle(): string
    {
        return 'Edit ' . ($this->record->name ?? 'Edit Facility'); 
        
    }
>>>>>>> 96372de888e3390641465bcceb865ca09bae25c8
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
