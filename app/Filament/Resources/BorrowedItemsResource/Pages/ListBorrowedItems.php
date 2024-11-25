<?php


namespace App\Filament\Resources\BorrowedItemsResource\Pages;

use App\Filament\Resources\BorrowedItemsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use App\Models\BorrowedItems;


class ListBorrowedItems extends ListRecords
{
    use \EightyNine\Approvals\Traits\HasApprovalHeaderActions;
    
    protected static string $resource = BorrowedItemsResource::class;
    protected ?string $heading = 'Borrowed Items';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
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
