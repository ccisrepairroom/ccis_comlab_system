<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BorrowList;
use Illuminate\Support\Facades\Auth;


    class BorrowListBadge extends Component
    {
        public $count;
    
        protected $listeners = ['borrowListUpdated' => 'updateCount'];
    
        public function mount()
        {
            $this->updateCount();
        }
    
        public function updateCount()
        {
            // Count the borrow lists, depending on the user's role
            if (Auth::check() && Auth::user()->hasRole('panel_user')) {
                $this->count = BorrowList::where('user_id', Auth::id())->count();
            } else {
                $this->count = BorrowList::count();
            }
        }
    
        public function render()
        {
            return view('livewire.borrow-list-badge');
        }
    }

