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
use App\Models\Facility;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;




class ListFacilities extends ListRecords
{
    protected static string $resource = FacilityResource::class;

   

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isFaculty = $user->hasRole('faculty'); // Check if the user has the 'panel_user' role
        
        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        if (!$isFaculty) {
            // Only add the import action if the user is not a panel_user
            $actions[] = Action::make('importFacility')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                    ->label('Import an Excel file. Column headers must include: Name, Connection Type, Facility Type, Floor level, Cooling Tools, Building and Remarks.
                     It is okay to have null fields in Excel as long as all the column headers are present.'),
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
    protected function getAllFacilityCount(): int
    {
        return Facility::count();
    }
    protected function getFirstFloorFacilityCount(): int
    {
        return Facility::where('floor_level', '1st Floor')->count();
    }
    protected function getSecondFloorFacilityCount(): int
    {
        return Facility::where('floor_level', '2nd Floor')->count();
    }
    protected function getThirdFloorFacilityCount(): int
    {
        return Facility::where('floor_level', '3rd Floor')->count();
    }
    protected function getFourthFloorFacilityCount(): int
    {
        return Facility::where('floor_level', '4th Floor')->count();
    }
    protected function getTableQuery(): ?Builder
    {
        // Get the base query and order it by the latest created_at field
        return parent::getTableQuery()->latest('created_at');
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge($this->getAllFacilityCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->orderBy('floor_level', 'asc');; // No filtering, display all records
                }),
            Tab::make('1st Floor')
                ->badge($this->getFirstFloorFacilityCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '1st Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('2nd Floor')
                ->badge($this->getSecondFloorFacilityCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '2nd Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('3rd Floor')
                ->badge($this->getThirdFloorFacilityCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '3rd Floor')
                    ->orderBy('facility_type');
                }),
            Tab::make('4th Floor')
                ->badge($this->getFourthFloorFacilityCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('floor_level', '4th Floor')
                    ->orderBy('facility_type');
                }),
            
        ];
    }
    protected function getTableActions(): array
    {
        return []; // This disables any default row actions (such as view)
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
