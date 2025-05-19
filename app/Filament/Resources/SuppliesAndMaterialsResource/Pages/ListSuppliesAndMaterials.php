<?php

namespace App\Filament\Resources\SuppliesAndMaterialsResource\Pages;

use App\Filament\Resources\SuppliesAndMaterialsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Imports\SuppliesImport;
use App\Models\SuppliesAndMaterials;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;


class ListSuppliesAndMaterials extends ListRecords
{
    protected static string $resource = SuppliesAndMaterialsResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); 
        $isFaculty = $user->hasRole('faculty'); 

        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        if (!$isFaculty) {
            $actions[] = Action::make('importSupplies')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                        ->label('Import an Excel file. Column headers must include: Item, Category, Quantity, Stocking Point, Stock Unit, Facility, Supplier, Date Acquired, and Remarks. It is okay to have null fields in Excel as long as all the column headers are present.'),
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
        $criticalStocks = SuppliesAndMaterials::whereColumn('quantity', '<=', 'stocking_point')
            ->where('quantity', '>=', 0) 
            ->whereNotNull('quantity') 
            ->whereNotNull('stocking_point') 
            ->get();

        foreach ($criticalStocks as $stock) {
            $existingNotification = DB::table('notifications')
                ->where('type', '=', 'critical_stock_alert') 
                ->where('data->supplies_and_materials_id', $stock->id)
                ->exists();

            if ($existingNotification) {
                DB::table('notifications')
                    ->where('data->supplies_and_materials_id', $stock->id)
                    ->where('type', '=', 'critical_stock_alert')
                    ->update([
                        'updated_at' => now() 
                    ]);
            } else {
                $recipient = auth()->user();
                $recipient->notify(
                    Notification::make()
                        ->title('Critical Stock Alert')
                        ->color('danger')
                        ->body("Item '{$stock->item}' is critically low with a quantity of {$stock->quantity}.")
                        ->toDatabase([
                            'type' => 'critical_stock_alert', 
                            'supplies_and_materials_id' => $stock->id, 
                        ])
                );
            }
        }

        return $criticalStocks->count(); 
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function query(): Builder
    {
        return parent::query()->latest('created_at');
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All Supplies And Materials')
                ->badge($this->getAllSuppliesCount()),
            Tab::make('Critical Stocks')
                ->badge($this->getCriticalStocksCount()) 
                ->modifyQueryUsing(function ($query) {
                    return $query->whereColumn('quantity', '<=', 'stocking_point')
                        ->where('quantity', '>=', 0) 
                        ->whereNotNull('quantity') 
                        ->whereNotNull('stocking_point'); 
                }),
        ];
    }
}
