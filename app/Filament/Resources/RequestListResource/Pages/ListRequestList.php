<?php

namespace App\Filament\Resources\RequestListResource\Pages;

use App\Filament\Resources\RequestListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRequestList extends ListRecords
{
    protected static string $resource = RequestListResource::class;

    protected ?string $heading = 'Request List';
    
    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->orderBy('created_at', 'desc'); 
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadRequestForm')
                ->label('Download Request Form')
                ->color('primary')
                ->url(route('download.request.form'))
                ->openUrlInNewTab(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
