<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Validation\Rules\Password;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
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
                            ->default(null)
                            //->unique('users', 'name')
                            //->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))

                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->rules([
                                'regex:/^[\w\.-]+@carsu\.edu\.ph$/', // Custom regex for domain check
                            ])
                            ->default(''),
                        Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->default([])
                            ->searchable(),
                        Forms\Components\Select::make('department')
                            ->options([
                                'Not Applicable'=> 'Not Applicable',
                                'Information System' => 'Information System',
                                'Information Technology' => 'Information Technology',
                                'Computer Science' => 'Computer Science',
                            ]),
                        Forms\Components\Select::make('designation')
                            //->required()
                            ->options([
                                'CCIS Dean'=>    'CCIS Dean',
                                'Lab Technician' =>  'Lab Technician',
                                'Comlab Adviser' =>'Comlab Adviser' ,
                                'Department Chairperson' =>  'Department Chairperson',
                                'Associate Dean' =>    'Associate Dean',
                                'College Clerk' => 'College Clerk',
                                'Student Assistant' => 'Student Assistant',
                                'Instructor' => 'Instructor',
                                'Lecturer' => 'Lecturer' ,
                                'Other' => 'Other',
    
                                
                            ]),
                
                           
                        Forms\Components\TextInput::make('password')->confirmed()
                            ->password()
                            ->required()
                            ->revealable()
                            //->default(fn($record) => $record->password)  
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->visible(fn ($livewire) =>$livewire instanceof Pages\CreateUser),
                            //->rule(Password::default ())
                            //->hiddenOn('edit'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            //->same('password')                          
                            ->requiredWith('password')
                            ->revealable()
                            ->visible(fn ($livewire) =>$livewire instanceof Pages\CreateUser),
                    ]),
                    /*Section::make('User New Password')->schema([
                        
                        Forms\Components\TextInput::make('new_password')
                            ->password()
                            ->nullable()
                            //->required()
                            //->required()
                            //(fn (?User $record) => $record === null)
                            ->revealable(),
                            //->dehydrateStateUsing(fn($state) => Hash::make($state))
                            //->visible(fn ($livewire) =>$livewire instanceof EditUser)
                            //->rule(Password::default ()),
                            //->hiddenOn('edit'),
                            TextInput::make('new_password_confirmation')
                            ->password()
                            //->same('password')                          
                            ->same('new_password')
                            ->revealable(),
                         
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditUser),*/
                    
            ]);
    }


    public static function afterSave(Model $record, array $data): void
        {
            // If there are roles to assign, sync them
            if (isset($data['roles'])) {
                $record->roles()->sync($data['roles']); // This will attach the selected roles to the user
            }

            // If the password was provided, it will be updated during the save process
            if (isset($data['password']) && $data['password']) {
                $record->password = Hash::make($data['password']); // Ensure password is hashed
                $record->save();
            }
        }
    public static function table(Tables\Table $table): Tables\Table
    {
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    

        // Conditionally add ExportBulkAction
        if (!$isFaculty) {
            $bulkActions[] = BulkAction::make('export')
                ->label('Export')
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
            ->description('This page contains the list of all users. For more information, go to the dashboard to download the user manual.')
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
                Tables\Columns\TextColumn::make('department')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('designation')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('password')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)  
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
                SelectFilter::make('department')
                ->label('Department')
                ->options(
                    User::query()
                        ->whereNotNull('department') // Filter out null values
                        ->pluck('department', 'department')
                        ->toArray()
                ),
                SelectFilter::make('designation')
                ->label('Designation')
                ->options(
                    User::query()
                        ->whereNotNull('designation') // Filter out null values
                        ->pluck('designation', 'designation')
                        ->toArray()
                ),
                SelectFilter::make('created_at')
                ->label('Created At')
                ->options(
                    User::query()
                        ->whereNotNull('created_at') // Filter out null values
                        ->distinct() // Ensure distinct dates
                        ->get(['created_at'])
                        ->mapWithKeys(function ($user) {
                            // Parse created_at as a Carbon instance
                            $date = \Carbon\Carbon::parse($user->created_at);
                            // Use only the date (Y-m-d) for filtering purposes
                            return [$date->toDateString() => $date->format('F j, Y')];
                        })
                        ->toArray()
                    ),
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
