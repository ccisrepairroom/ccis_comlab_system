<?php

namespace App\Filament\Resources\SuppliesAndMaterialsResource\Pages;

use App\Filament\Resources\SuppliesAndMaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuppliesAndMaterials extends CreateRecord
{
    protected static string $resource = SuppliesAndMaterialsResource::class;
    protected function getRedirectUrl(): string
    {
        return SuppliesAndMaterialsResource::getUrl('index'); // Redirect to the index page after creation
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
