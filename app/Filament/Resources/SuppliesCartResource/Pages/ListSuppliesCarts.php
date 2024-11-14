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

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isPanelUser = $user->hasRole('panel_user'); // Check if the user has the 'panel_user' role

       
    
        if (!$isPanelUser) {
            // Only add the import action if the user is not a panel_user
            $actions[] = Action::make('importSuppliesCart')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new SuppliesCartImport, $file);

                    Notification::make()
                        ->title('Supplies Cart Imported')
                        ->success()
                        ->send();
                });
        }
        

        return $actions;
    }
    
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        // Get the base query and order it by the latest created_at field
        return parent::getTableQuery()->latest('created_at');
    }
    
}
