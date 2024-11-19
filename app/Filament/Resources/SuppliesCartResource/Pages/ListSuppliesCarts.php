<?php

namespace App\Filament\Resources\SuppliesCartResource\Pages;

use App\Filament\Resources\SuppliesCartResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Imports\SuppliesCartImport;
use App\Models\SuppliesAndMaterials;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;

class ListSuppliesCarts extends ListRecords
{
    protected static string $resource = SuppliesCartResource::class;

    /*protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin'); // Check if the user has the 'super_admin' role

        if ($isSuperAdmin) {
            $actions[] = Action::make('importSuppliesCart')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) use ($user) {
                    $file = public_path('storage/' . $data['attachment']);

                    // Pass authenticated user's ID to SuppliesCartImport
                    Excel::import(new SuppliesCartImport($user->id), $file);

                    Notification::make()
                        ->title('Supplies Cart Imported')
                        ->success()
                        ->send();
                });
        }

        return $actions;
    }*/

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->latest('created_at');
    }
}
