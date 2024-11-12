<?php

namespace App\Filament\Resources\SuppliesAndMaterialsResource\Pages;

use App\Filament\Resources\SuppliesAndMaterialsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Imports\SuppliesImport;
use App\Models\SuppliesAndMaterials;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;




class ListSuppliesAndMaterials extends ListRecords
{
    protected static string $resource = SuppliesAndMaterialsResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isPanelUser = $user->hasRole('panel_user'); // Check if the user has the 'panel_user' role

        $actions = [
            Actions\CreateAction::make()
            ->label('Create'),
            
        ];
    
        if (!$isPanelUser) {
            // Only add the import action if the user is not a panel_user
            $actions[] = Action::make('importSupplies')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new SuppliesImport, $file);

                    Notification::make()
                        ->title('Supplies and Materials Imported')
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
