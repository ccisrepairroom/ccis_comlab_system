<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Spatie\Permission\Models\Role; 
use App\Models\User;
use Filament\Resources\Components\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Fetch all roles
        $roles = Role::all();
        
        // Create an array of tabs
        $tabs = [];
        
        // Add an "All" tab to show all users
        $tabs[] = Tab::make('All')
            ->badge(User::count())
            ->modifyQueryUsing(fn($query) => $query);

        foreach ($roles as $role) {
            $tabs[] = Tab::make($this->formatLabel($role->name)) // Format the role name
                ->badge($role->users()->count()) 
                ->modifyQueryUsing(fn($query) => $query->whereHas('roles', function($q) use ($role) {
                    $q->where('name', $role->name);
                }));
        }

        return $tabs;
    }

    // Helper function to format the role name
    protected function formatLabel(string $label): string
    {
        return ucwords(str_replace('_', ' ', $label)); // Replace underscores with spaces and capitalize
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
