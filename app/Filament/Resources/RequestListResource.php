<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestListResource\Pages;
use App\Filament\Resources\RequestListtResource\RelationManagers;
use App\Models\RequestList;
use App\Models\User;
use App\Models\BorrowedItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;



class RequestListResource extends Resource
{
    protected static ?string $model = RequestList::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Borrowing';
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    protected static ?string $navigationLabel = 'Request List';
   protected static ?int $navigationSort = 1;

   public static function getSlug(): string
    {
        return 'request-list'; 
    }

    public static function getNavigationBadge(): ?string
    {
        // Check if the user is authenticated and has the 'panel_user' role
        if (Auth::check() && Auth::user()->hasRole('panel_user')) {
            // Count only the records where 'user_id' matches the logged-in user's ID
            return static::getModel()::where('user_id', Auth::id())->count();
        }

        // If the user is not a 'panel_user', return the total count
        return static::getModel()::count();
    }

    /*public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id')->toArray())
                    ->disabled()
                    ->required()
                    ->default(fn($record) => $record->user ? $record->user->id : null),
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'description')
                    ->required()
                    ->helperText('Leave blank if inapplicable.'),
                Forms\Components\Select::make('borrowed_by')
                    ->required(),
                Forms\Components\Select::make('facility_id')
                    ->relationship('equipment', 'facility.name')
                    ->required()
                    ->helperText('Leave blank if inapplicable.'),
            ]);
    }*/

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        // Add EditAction only if the user is not a panel_user
        if (!$isFaculty) {
            $actions[] = Tables\Actions\EditAction::make();
        }

        // Define actions based on user role
        $actions = [
            Tables\Actions\ActionGroup::make([

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-arrow-right')
                    ->form([
                       /* Forms\Components\View::make('download_link')
                                ->view('components.download-link'),*/
                        Forms\Components\Grid::make([
                            'default' => 2,
                        ])->schema([
                            
                            Forms\Components\DateTimePicker::make('date')
                                
                                ->native(false)
                                ->format('M d, Y h:i A')
                                ->closeOnDateSelection(false)
                                ->withoutSeconds()
                                ->timezone('Asia/Manila') // Set timezone to Asia/Manila
                                ->default(now('Asia/Manila')) // Set default to current time in Manila
                                ->required()
                                ->disabled()
                                ->extraAttributes([
                                    'data-clock-format' => '12',
                                ]),
                            Forms\Components\TextInput::make('borrowed_by')
                                ->required(),
                                //->default(fn () => Auth::user() ? Auth::user()->name : ''),                                       
                            Forms\Components\TextInput::make('purpose')
                                ->required()
                                ->default('Course/Class Lecture')
                                ->placeholder('Project Requirements etc.,'),
                            Forms\Components\DateTimePicker::make('start_date_and_time_of_use')
                                ->native(false)
                                ->format('M d, Y h:i A')
                                ->closeOnDateSelection(false)
                                ->withoutSeconds()
                                ->default(now('Asia/Manila'))                               
                                ->required()
                                ->extraAttributes([
                                    'data-clock-format' => '12', 
                                ]),
                            Forms\Components\DateTimePicker::make('end_date_and_time_of_use')
                                ->native(false)
                                ->format('M d, Y h:i A')
                                ->closeOnDateSelection(false)
                                ->withoutSeconds()
                                ->default(now('Asia/Manila'))                               
                                ->required()
                                ->extraAttributes([
                                    'data-clock-format' => '12', 
                                ]),
                            Forms\Components\DateTimePicker::make('expected_return_date')
                                ->native(false)
                                ->format('M d, Y h:i A')
                                ->closeOnDateSelection(false)
                                ->withoutSeconds()
                                ->default(now('Asia/Manila'))                               
                                ->required()
                                ->extraAttributes([
                                    'data-clock-format' => '12', 
                                ]),
                            Forms\Components\TextInput::make('college_department_office')
                                ->required()
                                ->default('CCIS')
                                ->placeholder('CCIS'),
                            /*Forms\Components\View::make('download_link')
                                ->view('components.download-link'),*/
                        ]),
                        Forms\Components\FileUpload::make('request_form')
                            ->label('Signed Request Form/Image for proof. Must be an image or PDF file')
                            ->disk('public')
                            ->maxSize(921600)
                            ->required()
                            ->directory('request_forms')
                            ->preserveFilenames()
                    ])
                    ->action(function ($data, $record) {
                        // Ensure the record exists and data is valid
                        if ($record && isset($data['request_form'])) {
                            $requestlist = RequestList::find($record->id);

                            if ($requestlist) {
                                BorrowedItems::create([
                                    'user_id' => $requestlist->user_id,
                                    'equipment_id' => $requestlist->equipment_id,
                                    'facility_id' => $requestlist->facility_id,
                                    'request_status' => 'Pending',
                                    'borrowed_by' => $data['borrowed_by'],
                                    'request_form' => $data['request_form'],
                                    'date' => now(),
                                    'purpose' => $data['purpose'],
                                    'start_date_and_time_of_use' => $data['start_date_and_time_of_use'],
                                    'end_date_and_time_of_use' => $data['end_date_and_time_of_use'],
                                    'expected_return_date' => $data['expected_return_date'],
                                 
                                    'college_department_office' => $data['college_department_office'],
                                    'borrowed_date' => now(),
                                    'remarks' => '',
                                ]);

                                $requestlist->delete();

                                Notification::make()
                                    ->success()
                                    ->title('Success')
                                    ->body('Selected item/s have been transferred to borrowed items.')
                                    ->send();
                            } else {
                                Notification::make()
                                    ->error()
                                    ->title('Error')
                                    ->body('Request list record not found.')
                                    ->send();
                            }
                        } else {
                            Notification::make()
                                ->danger()
                                //->error()
                                ->title('Error')
                                ->body('Invalid data or record.')
                                ->send();
                        }
                    })
                    ->hidden(fn () => $isFaculty)
                    ->color('success'),
            ])->label('Actions') // Optional: You can label the action group
        ];

        // Remove null values
        $actions = array_filter($actions, fn($action) => $action !== null);

        return $table
            // Uncomment and adjust query modification if needed
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user() && Auth::user()->hasRole('panel_user')) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->description('Wait until an admin or staff approves your request. Once approved, it will be added to borrowed items.
            For more information, go to the dashboard to download the user manual.')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                ->label('Requested at')
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.po_number')
                    ->label('PO Number')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.brand_name')
                    ->label('Equipment')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.description')
                    ->label('Description')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                   
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.unit_no')
                    ->label('Unit Number')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Facility')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn($state) => $state ?? $state->equipment->facility->name ?? 'N/A'),

                Tables\Columns\TextColumn::make('equipment.category.description')
                    ->label('Category')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'working' => 'success',
                        'for repair' => 'warning',
                        'for replacement' => 'primary',
                        'lost' => 'danger',
                        'for disposal' => 'primary',
                        'disposed' => 'danger',
                        'borrowed' => 'indigo',
                        default => 'default', 
                    }),
                Tables\Columns\TextColumn::make('equipment.control_no')
                    ->label('Control Number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.serial_no')
                    ->label('Serial Number')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.property_no')
                    ->label('Property Number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.remarks')
                    ->label('Remarks')
                    ->toggleable(isToggledHiddenByDefault: true)
                    
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.person_liable')
                    ->label('Person_liable')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                
            ])
            ->filters([
                SelectFilter::make('created_at')
                    ->label('Requested at')
                    ->options(
                        RequestList::query()
                            ->whereNotNull('created_at') // Filter out null values
                            ->pluck('created_at') // Get only the 'created_at' values
                            ->map(function ($date) {
                                // Format date using Carbon
                                return \Carbon\Carbon::parse($date)->format('F j, Y');
                            })
                            ->unique() // Ensure unique values
                            ->toArray()
                    ),
                    SelectFilter::make('User')
                    ->label('Created By')
                    ->relationship('user','name'),
                
                   
                    
                ])
            ->actions($actions) // Apply the filtered actions
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-arrow-right')
                        ->form([
                            Forms\Components\Grid::make([
                                'default' => 1,
                            ])
                                ->schema([
                                    Forms\Components\DateTimePicker::make('date')
                                    ->native(false)
                                    ->format('M d, Y h:i A')
                                    ->closeOnDateSelection(false)
                                    ->withoutSeconds()
                                    ->default(now('Asia/Manila'))                               
                                    ->required()
                                    ->disabled()
                                    ->extraAttributes([
                                        'data-clock-format' => '12', 
                                    ]),
                                    Forms\Components\TextInput::make('borrowed_by')
                                        //->default(fn () => Auth::user() ? Auth::user()->name : '')                                      
                                        ->required(),
                                    Forms\Components\TextInput::make('purpose')
                                        ->required()
                                        ->default('Course/Class Lecture')
                                        ->placeholder('Project Requirements etc.,'),
                                    Forms\Components\DateTimePicker::make('start_date_and_time_of_use')
                                    ->native(false)
                                    ->format('M d, Y h:i A')
                                    ->closeOnDateSelection(false)
                                    ->withoutSeconds()
                                    ->default(now('Asia/Manila'))                               
                                    ->required()
                                    ->extraAttributes([
                                        'data-clock-format' => '12', 
                                    ]),
                                    Forms\Components\DateTimePicker::make('end_date_and_time_of_use')
                                    ->native(false)
                                    ->format('M d, Y h:i A')
                                    ->closeOnDateSelection(false)
                                    ->withoutSeconds()
                                    ->default(now('Asia/Manila'))                               
                                    ->required()
                                    ->extraAttributes([
                                        'data-clock-format' => '12', 
                                    ]),
                                    Forms\Components\DateTimePicker::make('expected_return_date')
                                    ->native(false)
                                    ->format('M d, Y h:i A')
                                    ->closeOnDateSelection(false)
                                    ->withoutSeconds()
                                    ->default(now('Asia/Manila'))                               
                                    ->required()
                                    ->extraAttributes([
                                        'data-clock-format' => '12', 
                                    ]),
                                    Forms\Components\TextInput::make('college_department_office')
                                        ->required()
                                        ->default('CCIS'),
                                    /*Forms\Components\View::make('download_link')
                                        ->view('components.download-link'),*/
                                ]),
                            Forms\Components\FileUpload::make('request_form')
                                ->label('Signed Request Form/Image for proof. Must be an image or PDF file')
                                ->disk('public')
                                ->maxSize(921600)
                                ->required()
                                ->directory('request_forms')
                                ->preserveFilenames()
                        ])
                        ->action(function (Collection $records, array $data) {
                            // Ensure data is valid
                            if (isset($data['request_form'])) {
                                foreach ($records as $record) {
                                    // Fetch the record to ensure it exists
                                    $requestlist = RequestList::find($record->id);

                                    if ($requestlist) {
                                        BorrowedItems::create([
                                            'user_id' => $requestlist->user_id,
                                            'equipment_id' => $requestlist->equipment_id,
                                            'facility_id' => $requestlist->facility_id,
                                            'request_status' => 'Pending',
                                            'request_form' => $data['request_form'],
                                            'date' => now(),
                                            'purpose' => $data['purpose'],
                                            'borrowed_by' => $data['borrowed_by'],
                                            'start_date_and_time_of_use' => $data['start_date_and_time_of_use'],
                                            'end_date_and_time_of_use' => $data['end_date_and_time_of_use'],
                                            'expected_return_date' => $data['expected_return_date'],
                                            'college_department_office' => $data['college_department_office'],
                                            'borrowed_date' => now()->format('Y-m-d h:i A'),
                                        ]);

                                        $requestlist->delete();
                                    }
                                }

                                Notification::make()
                                    ->success()
                                    ->title('Success')
                                    ->body('Selected item/s have been transferred to borrowed items.')
                                    ->send();
                            } else {
                                Notification::make()
                                    ->danger()
                                //->error()
                                    ->title('Error')
                                    ->body('Invalid data or request form.')
                                    ->send();
                            }
                        })
                        ->hidden(fn () => $isFaculty)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-check')
                        ->modalHeading('Add to Borrowed Items')
                        ->modalDescription('Confirm to add selected item/s to your borrowed items.'),
                ])
            ]);
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
            'index' => Pages\ListRequestList::route('/'),
            'create' => Pages\CreateRequestList::route('/create'),
            //'edit' => Pages\EditBorrowList::route('/{record}/edit'),
        ];
    }
}
