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

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

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
            $actions[] = Action::make('importEquipment')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment'),
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

            Tab::make('All')
                ->modifyQueryUsing(function ($query) {
                    return $query  ->orderBy('facility_id', 'asc') // No filtering, display all records
                    //->orderBy('unit_no' , 'desc')
                    ->orderBy('category_id');

                }),

            Tab::make('Working')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Working') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Repair')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Repair') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Replacement')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Replacement') ->orderBy('created_at', 'desc');
                }),
            Tab::make('Lost')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Lost') ->orderBy('created_at', 'desc');
                }),
            Tab::make('For Disposal')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'For Disposal') ->orderBy('created_at', 'desc');
                }),
            Tab::make('Disposed')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Disposed') ->orderBy('created_at', 'desc');
                }),
            Tab::make('Borrowed')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', 'Unreturned') ->orderBy('created_at', 'desc');
                }),
        ];
    }
}
