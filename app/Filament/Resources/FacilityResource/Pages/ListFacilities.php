<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Actions\Action; 
use Filament\Resources\Pages\ListRecords;
use App\Imports\FacilityImport;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;


class ListFacilities extends ListRecords
{
    protected static string $resource = FacilityResource::class;

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
            $actions[] = Action::make('importFacility')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new FacilityImport, $file);

                    Notification::make()
                        ->title('Facilities Imported')
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

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->modifyQueryUsing(function ($query) {
                    return $query->orderBy('floor_level', 'asc');; // No filtering, display all records
                }),
            Tab::make('1st Floor')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '1st Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('2nd Floor')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '2nd Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('3rd Floor')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '3rd Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('4th Floor')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '4th Floor')
                    ->orderBy('facility_type');
                }),
            
        ];
    }
}








       /* return [
            Actions\CreateAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }

}*/
