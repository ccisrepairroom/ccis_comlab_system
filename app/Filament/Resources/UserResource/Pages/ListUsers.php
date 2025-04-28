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
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isFaculty = $user->hasRole('faculty'); // Check if the user has the 'faculty' role

        $actions = [
            Actions\CreateAction::make()
                ->label('Create'),
        ];

        /*if (!$isFaculty) {
            // Only add the import action if the user is not faculty
            $actions[] = Action::make('importUsers')
                ->label('Import')
                ->color('success')
                ->button()
                ->form([
                    FileUpload::make('attachment')
                        ->label('Import an Excel file. Column headers must include: Name, Role, Email, and Password.'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);

                    Excel::import(new UserImport, $file);

                    Notification::make()
                        ->title('Users Imported')
                        ->success()
                        ->send();
                });
        }*/

        return $actions;
    }

    public function getTabs(): array
    {
        // Fetch role counts based on role_id
        $roleCounts = User::select('role_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('role_id')
            ->pluck('count', 'role_id');

        // Fetch all roles that have users
        $roles = Role::whereIn('id', $roleCounts->keys())->get();

        // Create an array of tabs
        $tabs = [];

        // Add an "All" tab to show all users
        $tabs[] = Tab::make('All')
            ->badge(User::count())
            ->modifyQueryUsing(fn($query) => $query);

        foreach ($roles as $role) {
            $tabs[] = Tab::make($this->formatLabel($role->name)) // Format the role name
                ->badge($roleCounts[$role->id] ?? 0)
                ->modifyQueryUsing(fn($query) => $query->where('role_id', $role->id));
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
