<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    // public function canAccessPanel(Panel $panel): bool
    // {
    //     $user = auth()->user(); // Get the authenticated user
    //     $userRole = $user->roles()->pluck('name')->first(); // Assuming the user has a single role

    //     // Get the panel id or path
    //     $panelId = $panel->getId(); // Method to get panel ID, adjust if necessary

    //     // Define restricted panels for each role
    //     $restrictedPanels = [
    //         'panel_user' => ['admin', 'admin_user'], // Panel_user cannot access admin and admin_user panels
    //         'Staff' => ['admin'], // Staff cannot access admin panel
    //         'Admin' => ['admin'], // Admin cannot access admin panel
    //     ];

    //     // Check access based on the user's role
    //     if (isset($restrictedPanels[$userRole])) {
    //         return !in_array($panelId, $restrictedPanels[$userRole]);
    //     }

    //     // Other roles can access all panels
    //     return true;
    // }

    // protected function getPanelId(Panel $panel): string
    // {
    //     // Assuming the panel ID is set in the configuration
    //     return $panel->id(); // Adjust this method based on how you access the ID
    // }





    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function countUsersByRole(string $roleName): int
    {
        return User::role($roleName)->count();
    }
}