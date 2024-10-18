<?php

namespace App\Filament\AdminUser\Resources\BorrowResource\Pages;

use App\Filament\AdminUser\Resources\BorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrow extends CreateRecord
{
    protected static string $resource = BorrowResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
