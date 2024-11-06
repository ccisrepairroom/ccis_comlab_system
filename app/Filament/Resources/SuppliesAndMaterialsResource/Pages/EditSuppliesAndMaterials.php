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
<<<<<<< HEAD
    protected function getRedirectUrl(): string
    {
        return SuppliesAndMaterialsResource::getUrl('index'); // Redirect to the index page after creation
=======
    public function getTitle(): string
    {
        return 'Edit ' . ($this->record->item ?? 'Edit Supplies and Materials'); 
        
>>>>>>> 96372de888e3390641465bcceb865ca09bae25c8
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
<<<<<<< HEAD

=======
>>>>>>> 96372de888e3390641465bcceb865ca09bae25c8
}
