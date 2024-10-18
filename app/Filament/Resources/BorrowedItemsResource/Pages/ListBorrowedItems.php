<?php


namespace App\Filament\Resources\BorroweditemsResource\Pages;

use App\Filament\Resources\BorroweditemsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class ListBorrowedItems extends ListRecords
{
    use \EightyNine\Approvals\Traits\HasApprovalHeaderActions;
    
    protected static string $resource = BorroweditemsResource::class;
    protected ?string $heading = 'Borrowed Items';

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
