<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); 
        $isPanelUser = $user->hasRole('panel_user'); 

        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        if (!$isPanelUser) {
            $actions[] = Action::make('importCategory')
                ->label('Import')
                ->color('success')
                ->button()
                
                ->form([
                    FileUpload::make('attachment')
                    ->label('Import an excel file. Column header must include: Description.'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new CategoryImport, $file);

                    Notification::make()
                        ->title('Categories Imported')
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







