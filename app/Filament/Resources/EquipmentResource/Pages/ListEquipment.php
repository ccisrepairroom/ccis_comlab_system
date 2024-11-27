<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\EquipmentImport;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use App\Models\Equipment;
use App\Models\Borroweditems;


class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
  

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isFaculty = $user->hasRole('faculty'); // Check if the user has the 'panel_user' role
        
        $actions = [
           
            Actions\Action::make('downloadRequestForm')
                ->label('Download Request Form')
                //->icon('heroicon-o-download')
                ->color('primary')
                ->url(route('download.request.form'))
                ->openUrlInNewTab(),
        ];
        if (!$isFaculty) {
            $actions[] = Actions\CreateAction::make()
            ->label('Create');
        }
        

        if (!$isFaculty) {
            // Only add the import action if the user is not a panel_user
            $actions[] = Action::make('importEquipment')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                    ->label('Import an Excel file. Column headers must include: PO Number, Unit Number, Brand Name, Description, Facility, Category, Status,
                     Date Acquired, Supplier, Amount, Estimated Life, Item Number, Property Number, Control Number,  Serial Number, Person Liable, and Remarks.
                     It is okay to have null fields in Excel as long as all the column headers are present.')
                    
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new EquipmentImport, $file);

                    Notification::make()
                        ->title('Equipment Imported')
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
    protected function getAllEquipmentCount(): int
    {
        return Equipment::count();
    }
    

    /*protected function getTableColumns(): array
    {
        return [
            // Define your columns here
            Tables\Columns\TextColumn::make('source_of_fund')->label('Source of Fund'),
            Tables\Columns\TextColumn::make('description')->label('Description'),
            // ... Add other columns as necessary

        ];
    }
    protected function getTableRowAction(): array
    {
        return [
            Actions\Action::make('view_monitoring')
                ->label('View Monitoring Records')
                ->icon('heroicon-o-presentation-chart-line')
                ->color('info')
                ->action(function ($record) {
                    $this->showMonitoringRecordsModal($record);
                }),
        ];
    }

    protected function showMonitoringRecordsModal($record)
    {
        $equipmentId = $record->id;
        $monitorings = EquipmentMonitoring::with('equipment.facility', 'user')
            ->where('equipment_id', $equipmentId)
            ->get();

        return view('filament.resources.equipment-monitoring-modal', [
            'monitorings' => $monitorings,
        ]);
    }*/



    /*protected function getWorkingEquipmentCount(): int
    {
        return Equipment::where('status', 'Working')->count();
    }
    protected function getForRepairEquipmentCount(): int
    {
        return Equipment::where('status', 'For Repair')->count();
    }
    protected function getForReplacementEquipmentCount(): int
    {
        return Equipment::where('status', 'For Replacement')->count();
    }
    protected function getLostEquipmentCount(): int
    {
        return Equipment::where('status', 'Lost')->count();
    }
    protected function getForDisposalEquipmentCount(): int
    {
        return Equipment::where('status', 'For Disposal')->count();
    }
    protected function getDisposedEquipmentCount(): int
    {
        return Equipment::where('status', 'Disposed')->count();
    }*/
    protected function getBorrowedAndUnreturnedEquipmentCount(): int
    {
        return BorrowedItems::where('status', 'Unreturned')->count();
    }


    public function getTabs(): array
    {
        return [
            /*Tabs::make('Facilities', [
                Tab::make('All', function () {
                    return [
                        Text::make('Facility ID', 'facility_id')->sortable(),
                        Text::make('Unit No', 'unit_no')->sortable(),
                        // Add other fields as needed
                    ];
                })->withMeta([
                    'query' => function ($query) {
                        return $query->orderBy('facility_id', 'asc')
                                     ->orderBy('unit_no', 'asc'); // Order by both facility_id and unit_no
                    },
                ]),
                // Add other tabs if needed
            ]),
        ];
    }
}),
*/

            Tab::make('All Equipment')
                ->badge($this->getAllEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query  ->orderBy('facility_id', 'asc') // No filtering, display all records
                    //->orderBy('unit_no' , 'desc')
                    ->orderBy('category_id');

                }),

            /*Tab::make('Working')
                 ->badge($this->getWorkingEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Working') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Repair')
                ->badge($this->getForRepairEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Repair') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Replacement')
                ->badge($this->getForReplacementEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Replacement') ->orderBy('created_at', 'desc');
                }),
            Tab::make('Lost')
                ->badge($this->getLostEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Lost') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Disposal')
                ->badge($this->getForDisposalEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Disposal') ->orderBy('created_at', 'desc');
                }),
            Tab::make('Disposed')
                ->badge($this->getDisposedEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Disposed') ->orderBy('created_at', 'desc');
                }),*/
            Tab::make('Borrowed Items')
                ->badge($this->getBorrowedAndUnreturnedEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                return $query->whereHas('borrowedItems', function ($borrowedQuery) {
                    $borrowedQuery->where('status', 'Unreturned');
                })->orderBy('created_at', 'desc');
            }),
        ];
    }
}
