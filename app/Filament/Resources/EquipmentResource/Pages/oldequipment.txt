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
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $isAuthorized = $user->hasRole('admin') || $user->hasRole('superadmin');
        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        if ($isAuthorized) {

            $actions[] = Action::make('importEquipment')
                ->label('Import')
                ->color('success')
                ->button()
                // ->form([
                //     FileUpload::make('attachment'),
                // ])
                ->form([
                    FileUpload::make('attachment')
                        ->disk('local')
                        ->directory('imports') // Store in 'imports' folder within storage/app
                        ->preserveFilenames(),
                ])
                // ->action(function (array $data) {
                //     $file = public_path('storage/' . $data['attachment']);

                //     // dd($file);

                //     Excel::import(new EquipmentImport, $file);

                //     Notification::make()
                //         ->title('Equipment Imported')
                //         ->success()
                //         ->send();
                // });
                ->action(function (array $data) {
                    // Get the correct file path from storage
                    $filePath = $data['attachment'];

                    // Full path to file
                    $file = Storage::disk('local')->path($filePath);

                    // Perform the Excel import
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

    public function getTabs(): array
    {
        return [
            Tab::make('All')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('created_at', 'desc'); 
                }),
            Tab::make('Working')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'Working')->orderBy('created_at', 'desc');
                }),
            Tab::make('For Repair')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'For Repair')->orderBy('created_at', 'desc');
                }),
            Tab::make('For Replacement')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'For Replacement')->orderBy('created_at', 'desc');
                }),
            Tab::make('Lost')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'Lost')->orderBy('created_at', 'desc');
                }),
            Tab::make('For Disposal')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'For Disposal')->orderBy('created_at', 'desc');
                }),
            Tab::make('Disposed')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'Disposed')->orderBy('created_at', 'desc');
                }),
            /*Tab::make('Borrowed')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Borrowed');
                }),*/
        ];
    }
}
