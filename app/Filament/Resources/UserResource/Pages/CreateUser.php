<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Return an empty array to reset all fields to their default states
        return [];
    }
    
}
