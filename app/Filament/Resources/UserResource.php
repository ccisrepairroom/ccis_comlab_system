<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Filament\Tables\Actions\BulkAction;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function table(Tables\Table $table): Tables\Table
    {
        $user = auth()->user();
        $isPanelUser = $user && $user->hasRole('panel_user');

        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
        ];

        // Conditionally add ExportBulkAction
        if (!$isPanelUser) {
            $bulkActions[] = BulkAction::make('export')
                ->label('Export Users')
                ->icon('heroicon-o-arrow-down')
                ->action(function () {
                    // Fetch users with roles
                    $users = User::with('roles')->get();

                    // Prepare data for the CSV export
                    $data = $users->map(function ($user) {
                        return [
                            'Name' => $user->name,
                            'Email' => $user->email,
                            'Password' => $user->password,  // Include the password field explicitly
                            'Role' => $user->roles->pluck('name')->implode(', '),
                            'Created At' => $user->created_at->format('F j, Y h:i A'),
                        ];
                    });

                    // Create CSV content using League\Csv
                    $csv = Writer::createFromString('');
                    $csv->insertOne(['Name', 'Email', 'Password', 'Role', 'Created At']);
                    $csv->insertAll($data);

                    // Save the CSV file
                    $filePath = 'exports/users.csv';
                    Storage::put($filePath, $csv->getContent());

                    // Return the file for download
                    return response()->download(storage_path("app/{$filePath}"));
                });
        }

        return $table
            ->query(User::with('roles'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge(),
                Tables\Columns\TextColumn::make('password')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)  // Keep it hidden by default but it will be included in the export
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->format('F j, Y h:i A') : null;
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions')
            ]);
    }

    public static function getPermissions(User $user)
    {
        // Retrieve the user's permissions from the roles or any other source
        return $user->getAllPermissions()->pluck('name'); // Adjust according to your setup
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
