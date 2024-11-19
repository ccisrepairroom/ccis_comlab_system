<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
    public function getTitle(): string
    {
        return 'Edit ' . ($this->record->name ?? 'Edit User'); 
        
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
    /*protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle password hashing during editing
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }*/
}
