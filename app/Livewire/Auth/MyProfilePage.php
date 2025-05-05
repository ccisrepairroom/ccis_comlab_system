<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use App\Models\User;
use Spatie\Permission\Models\Role;


#[Title('My Profile - CCIS ERMA')]
class MyProfilePage extends Component
{
    public $name;
    public $email;
    public $department;
    public $designation;
    public $password;
    public $confirmPassword;
    public $role;
 



    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department = $user->department;
        $this->designation = $user->designation;
        $this->role = $user->getRoleNames()->first();


   

    }

    public function save()
    {
        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'department' => $this->department,
            'designation' => $this->designation,
        ]);
        session()->flash('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:6|same:confirmPassword',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($this->password),
        ]);

        $this->reset(['password', 'confirmPassword']);
        session()->flash('success', 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.auth.my-profile-page');
    }
}
