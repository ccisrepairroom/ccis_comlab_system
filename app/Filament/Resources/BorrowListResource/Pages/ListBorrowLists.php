<?php

namespace App\Filament\Resources\BorrowListResource\Pages;

use App\Filament\Resources\BorrowListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBorrowLists extends ListRecords
{
    protected static string $resource = BorrowListResource::class;

    protected ?string $heading = 'Request List';
    
    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->orderBy('created_at', 'desc'); // Order by the latest entries first
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadRequestForm')
                ->label('Download Request Form')
                //->icon('heroicon-o-download')
                ->color('primary')
                ->url(asset('storage/request_form/request_form.pdf'))
                ->openUrlInNewTab(),
        ];

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
    }
}
