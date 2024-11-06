<?php

namespace App\Filament\Resources\SuppliesCartResource\Pages;

use App\Filament\Resources\SuppliesCartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuppliesCart extends CreateRecord
{
    protected static string $resource = SuppliesCartResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }
}

