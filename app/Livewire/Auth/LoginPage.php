<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;
use App\Helpers\LoginManagement;


#[Title('Login - CCIS ERMA')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function mount()
    {
        // Retrieve remembered credentials
        $credentials = LoginManagement::getRememberedCredentials();
        $this->email = $credentials['email'];
    }

    public function save()
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|carsu\.edu\.ph)$/'
            ],
            'password' => 'required|min:6|max:255',
        ], [
            'email.regex' => 'Invalid email. Only @gmail.com and @carsu.edu.ph domains are allowed.',
            'email.exists' => 'This email does not exist in our database.',
        ]);

        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials.');
            return;
        }

         // Save credentials if "Remember Me" is checked
        LoginManagement::rememberUser($this->email, $this->remember);

        return redirect('http://127.0.0.1:8000')->intended();
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
