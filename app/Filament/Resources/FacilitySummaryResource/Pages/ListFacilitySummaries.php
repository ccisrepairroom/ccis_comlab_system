<?php

namespace App\Filament\Resources\FacilitySummaryResource\Pages;

use App\Filament\Resources\FacilitySummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacilitySummaries extends ListRecords
{
    protected static string $resource = FacilitySummaryResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
