<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\BorrowedItems;
use App\Helpers\RequestManagement;




#[Title('My Requests - CCIS ERMA')]

class MyRequestsPage extends Component
{
    use WithPagination;

    public function render()
    {
        $my_requests = BorrowedItems::where('user_id', auth()->id())->latest()->paginate(10);
        return view('livewire.my-requests-page', [
            'borrowed_items' => $my_requests,
        ]);
    }
}
