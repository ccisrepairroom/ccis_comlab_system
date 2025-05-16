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
        $this->validate([
            'name' => [
                'required',
                'max:50',
                'regex:/^[a-zA-Z0-9\s.]+$/'
            ],
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9.]+@(gmail\.com|carsu\.edu\.ph)$/'
            ],
            'department' => 'required',
            'designation' => 'required',
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 50 characters.',
            'name.regex' => 'Name must not contain special characters (only letters, numbers, spaces, and periods are allowed).',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.regex' => 'Email must end with @gmail.com or @carsu.edu.ph and only contain letters, numbers, and dots.',

            'department.required' => 'Department is required.',
            'designation.required' => 'Designation is required.',
        ]);

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
            'confirmPassword' => 'required|min:6',
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.same' => 'Password must match the confirmation password.',
            'confirmPassword.required' => 'Confirmation password is required.',
            'confirmPassword.min' => 'Confirmation password must be at least 6 characters.',
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
