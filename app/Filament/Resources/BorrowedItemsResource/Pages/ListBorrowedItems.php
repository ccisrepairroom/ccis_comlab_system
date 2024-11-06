<?php


namespace App\Filament\Resources\BorroweditemsResource\Pages;

use App\Filament\Resources\BorroweditemsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Models\Borroweditems;


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
    protected function getAllBorrowedCount(): int
    {
        return BorrowedItems::count();
    }
    protected function getUnreturnedBorrowedCount(): int
    {
        return BorrowedItems::where('status', 'Unreturned')->count();
    }
    protected function getReturnedBorrowedCount(): int
    {
        return BorrowedItems::where('status', 'Returned')->count();
    }

    public function getTabs(): array
    {
        return array_merge(
            [
                Tab::make('All')
                    ->badge($this->getAllBorrowedCount())
                    ->modifyQueryUsing(fn($query) => $query),
                Tab::make('Returned')
                    ->badge($this->getReturnedBorrowedCount())
                    ->modifyQueryUsing(fn($query) => $query->where('status', 'Returned')),
                Tab::make('Unreturned')
                    ->badge($this->getUnreturnedBorrowedCount())
                    ->modifyQueryUsing(fn($query) => $query->where('status', 'Unreturned')),
            ]
        );
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
