<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('My Profile - CCIS ERMA')]
class MyProfilePage extends Component
{
    public function render()
    {
        return view('livewire.auth.my-profile-page');
    }
}
