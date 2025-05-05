<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;
use App\Helpers\LoginManagement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

#[Title('Sign In - CCIS ERMA')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function mount()
    {
        // Retrieve remembered credentials securely
        $credentials = LoginManagement::getRememberedCredentials();
        if (!empty($credentials['email'])) {
            $this->email = Crypt::decryptString($credentials['email']);
        }
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

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            session()->flash('error', 'Invalid credentials.');
            return;
        }

        auth()->login($user);

        // Securely save credentials if "Remember Me" is checked
        if ($this->remember) {
            LoginManagement::rememberUser(Crypt::encryptString($this->email), true);
        }

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
