<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;


#[Title('Register - CCIS ERMA')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $contact_number;
    public $password;
    
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
