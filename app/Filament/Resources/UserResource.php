<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Model;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        \Log::info($record);
        
        return [
            'Name' => $record->name ?? 'Unknown', 
            'Email' => $record->email ?? 'Unknown', 
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return['name','email'];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            //->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))

                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->hiddenOn('edit')
                    ])
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {

        $user = auth()->user();
        $isPanelUser = $user && $user->hasRole('panel_user');

         // Define the bulk actions array
         $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            //Tables\Actions\ExportBulkAction::make()

         ];
                 // Conditionally add ExportBulkAction

            if (!$isPanelUser) {
                $bulkActions[] = ExportBulkAction::make();
            }
            return $table
            ->query(User::with('roles'))
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        // Format the date and time
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
        // This may involve accessing roles and their associated permissions
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
