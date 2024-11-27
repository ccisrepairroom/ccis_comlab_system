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
        $isFaculty = $user->hasRole('faculty'); // Check if the user has the 'panel_user' role
        
        $actions = [
            Actions\CreateAction::make()
            ->label('Create'),
            
        ];
    
        if (!$isFaculty) {
            // Only add the import action if the user is not a panel_user
            $actions[] = Action::make('importSupplies')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                    ->label('Import an Excel file. Column headers must include: Item, Category, Quantity, Stocking Point, Stock Unit, Facility, Supplier, Date Acquired, and Remarks.
                     It is okay to have null fields in Excel as long as all the column headers are present.'),
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
    protected function getAllSuppliesCount(): int
    {
        return SuppliesAndMaterials::count();
    }
    protected function getCriticalStocksCount(): int
    {
        // Fetch supplies where quantity is less than or equal to the stocking point
        // Add a condition to ensure no null or negative quantities
        $criticalStocks = SuppliesAndMaterials::whereColumn('quantity', '<=', 'stocking_point')
            ->where('quantity', '>=', 0) // Ensure quantity is not negative
            ->whereNotNull('quantity') // Ensure quantity is not null
            ->whereNotNull('stocking_point') // Ensure stocking point is not null
            ->get();

        // Debugging: log the critical items to the error log for inspection
        \Log::debug('Critical Stocks: ', $criticalStocks->toArray());

        return $criticalStocks->count();
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

    public function getTabs(): array
    {
        return [
            Tab::make('All Supplies And Materials')
                ->badge($this->getAllSuppliesCount()),
            Tab::make('Critical Stocks')
                ->badge($this->getCriticalStocksCount()) // Add badge count for Critical Stocks
                ->modifyQueryUsing(function ($query) {
                    // Modify the query to return only critical supplies where quantity <= stocking_point
                    return $query->whereColumn('quantity', '<=', 'stocking_point')
                        ->where('quantity', '>=', 0) // Ensure quantity is not negative
                        ->whereNotNull('quantity') // Ensure quantity is not null
                        ->whereNotNull('stocking_point'); // Ensure stocking point is not null
                }),
        ];
    }
}