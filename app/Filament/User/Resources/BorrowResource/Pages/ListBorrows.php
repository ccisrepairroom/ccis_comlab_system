<?php

namespace App\Filament\User\Resources\BorrowResource\Pages;

use App\Filament\User\Resources\BorrowResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;


class ListBorrows extends ListRecords
{
    use  \EightyNine\Approvals\Traits\HasApprovalHeaderActions;
    protected static string $resource = BorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected ?string $heading = 'Request List';

    // /**
    //  * Get the completion action.
    //  *
    //  * @return Filament\Actions\Action
    //  * @throws Exception
    //  */
    // protected function getOnCompletionAction(): Action
    // {
    //     return Action::make("Done")
    //         ->color("success")
    //         // Do not use the visible method, since it is being used internally to show this action if the approval flow has been completed.
    //         // Using the hidden method add your condition to prevent the action from being performed more than once
    //         ->hidden(fn(ApprovableModel $record) => $record->shouldBeHidden());
    // }
}
