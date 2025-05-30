<?php

namespace App\Filament\Resources\StockUnitsResource\Pages;
use App\Filament\Resources\StockUnitsResource;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use App\Imports\StockUnitImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;



class ListStockUnits extends ListRecords
{

protected static string $resource = StockUnitsResource::class;
protected function getHeaderActions(): array
{
    $user = auth()->user(); 
    $isFaculty = $user->hasRole('faculty'); 

    $actions = [
        Actions\CreateAction::make()
            ->label('Create'),
    ];

    if (!$isFaculty) {
        $actions[] = Action::make('importStockUnit')
            ->label('Import')
            ->color('success')
            ->button()
            ->form([
                FileUpload::make('attachment')
                ->label('Import an excel file. Column header must include: Description.'),
            ])
            ->action(function (array $data) {
                $file = public_path('storage/' . $data['attachment']);

                Excel::import(new StockUnitImport, $file);

                Notification::make()
                    ->title('Stock Units Imported')
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
    return parent::getTableQuery()->latest('created_at');
}

}
