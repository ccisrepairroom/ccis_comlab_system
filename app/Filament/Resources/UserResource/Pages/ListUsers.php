<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Filament\Resources\Components\Tab;
use Filament\Actions\Action;
use App\Imports\UserImport;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); 
        $isFaculty = $user->hasRole('faculty'); 

        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        return $actions;
    }

    public function getTabs(): array
    {
        $roleCounts = User::select('role_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('role_id')
            ->pluck('count', 'role_id');

        $roles = Role::whereIn('id', $roleCounts->keys())->get();

        $tabs = [];

        $tabs[] = Tab::make('All')
            ->badge(User::count())
            ->modifyQueryUsing(fn($query) => $query);

        foreach ($roles as $role) {
            $tabs[] = Tab::make($this->formatLabel($role->name)) 
                ->badge($roleCounts[$role->id] ?? 0)
                ->modifyQueryUsing(fn($query) => $query->where('role_id', $role->id));
        }

        return $tabs;
    }

    // Helper function to format the role name
    protected function formatLabel(string $label): string
    {
        return ucwords(str_replace('_', ' ', $label)); 
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
