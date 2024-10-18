<?php


namespace App\Filament\Resources\BorrowResource\Pages;

use App\Filament\Resources\BorrowResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class ListBorrows extends ListRecords
{
    use \EightyNine\Approvals\Traits\HasApprovalHeaderActions;
    
    protected static string $resource = BorrowResource::class;
    protected ?string $heading = 'Transferred to Borrows';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->orderBy('created_at', 'desc'); // Order by the latest entries first
    }

    public function getTabs(): array
    {
        return array_merge(
            [
                Tab::make('All')
                    ->modifyQueryUsing(fn($query) => $query),
                Tab::make('Returned')
                    ->modifyQueryUsing(fn($query) => $query->where('status', 'Returned')),
                Tab::make('Unreturned')
                    ->modifyQueryUsing(fn($query) => $query->where('status', 'Unreturned')),
            ]
        );
    }
}
