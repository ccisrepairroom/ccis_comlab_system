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
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;


class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
  
    // protected function paginateTableQuery(Builder $query): Paginator
    // {
    //     return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    // }
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
            // Only add the import action if the user is not a faculty
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
    protected function getBorrowedAndUnreturnedEquipmentCount(): int
    {
        return BorrowedItems::where('status', 'Unreturned')->count();
    }


    public function getTabs(): array
    {
        return [

            Tab::make('All Equipment')
                ->badge($this->getAllEquipmentCount())
                ->modifyQueryUsing(function ($query) {
                    return $query  ->orderBy('created_at', 'desc') 
                    ->orderBy('category_id');

                }),
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
