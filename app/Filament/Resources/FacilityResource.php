<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use App\Models\Equipment;
use App\Models\FacilityMonitoring;
use App\Models\User;
use App\Models\BorrowedItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\ActionsPosition;



class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Facility Image')
                ->schema([
                    Forms\Components\FileUpload::make('main_image')
                    ->label('Main Image')
                    ->imageEditor()
                    ->deletable()
                    ->required()
                    ->preserveFilenames(),

                    Forms\Components\FileUpload::make('alternate_images')
                    ->imageEditor()
                    ->deletable()
                    ->multiple()
                    ->required()
                    ->preserveFilenames(),

 
                    ])
                    ->columnSpan(2)
                    ->columns(2)
                    ->collapsible(),

                Section::make('Facility Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->placeholder('Facility Name Displayed On The Door (e.g., CL1, CL2)')
                                    ->required()
                                    ->unique(
                                        table: 'facilities', 
                                        column: 'name', 
                                        ignoreRecord: true
                                    )
                                    ->validationMessages([
                                        'unique' => 'This facility name already exists.',
                                    ])
                                    ->maxLength(255),
                                Forms\Components\Select::make('connection_type')
                                    ->options([
                                        'None' => 'None',
                                        'Wi-Fi' => 'Wi-Fi',
                                        'Ethernet' => 'Ethernet',
                                        'Both Wi-fi and Ethernet' => 'Both Wi-fi and Ethernet',
                                        'Fiber Optic' => 'Fiber Optic',
                                        'Cellular' => 'Cellular',
                                        'Bluetooth' => 'Bluetooth',
                                        'Satellite' => 'Satellite',
                                        'DSL' => 'DSL',
                                        'Cable' => 'Cable',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('facility_type')
                                    ->options([
                                        'Room' => 'Room',
                                        'Office' => 'Office',
                                        'Computer Laboratory' => 'Computer Laboratory',
                                        'Incubation Hub' => 'Incubation Hub',
                                        'Robotic Hub' => 'Robotic Hub',
                                        'Hall' => 'Hall',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('cooling_tools')
                                    ->options([
                                        'None' => 'None',
                                        'Aircon' => 'Aircon',
                                        'Ceiling Fan' => 'Ceiling Fan',
                                        'Both Aircon and Ceiling Fan' => 'Both Aircon and Ceiling Fan',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('floor_level')
                                    ->options([
                                        '1st Floor' => '1st Floor',
                                        '2nd Floor' => '2nd Floor',
                                        '3rd Floor' => '3rd Floor',
                                        '4th Floor' => '4th Floor',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('building')
                                    ->required()
                                    ->default('HIRAYA'),
                            ]),
                    ]),
                Section::make('Remarks')
                    ->schema([
                        Forms\Components\RichEditor::make('remarks')
                            ->placeholder('Anything that describes the facility (e.g., Computer Laboratory with space for 30 students)')
                            ->disableToolbarButtons(['attachFiles']),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        // Define bulk actions
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            Tables\Actions\BulkAction::make('bulk_update')
                ->label('Bulk Update')
                ->icon('entypo-cycle')
                ->color('warning')
                ->requiresConfirmation()
                ->modalIcon('entypo-cycle')
                ->modalHeading('Bulk Update Facility Details')
                ->modalDescription('Select the fields you want to update.')
                ->form(function (Forms\Form $form) {
                    return $form->schema([
                        // Step 1: Select columns to update
                        Forms\Components\Select::make('monitored_by')
                            ->label('Monitored By')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->user()->id)
                            ->disabled()
                            ->required(),
                        Forms\Components\DatePicker::make('monitored_date')
                            ->label('Monitoring Date')
                            ->required()
                            ->disabled()
                            ->default(now())
                            ->format('Y-m-d'),

                        Forms\Components\CheckboxList::make('fields_to_update')
                            ->options([
                                'main_image' => 'Facility Image',
                                'connection_type' => 'Connection Type',
                                'facility_type' => 'Facility Type',
                                'cooling_tools' => 'Cooling Tools',
                                'floor_level' => 'Floor Level',
                                'building' => 'Building',
                                'remarks' => 'Remarks',
                            ])
                            ->columns(2)
                            ->reactive(), 

                        Forms\Components\FileUpload::make('main_image')
                            ->label('Main Image')
                            ->imageEditor()
                            ->deletable()
                            ->preserveFilenames()
                            ->visible(fn ($get) => in_array('main_image', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('main_image', $get('fields_to_update') ?? [])),

                        Forms\Components\FileUpload::make('alternate_images')
                            ->label('Alternate Images')
                            ->imageEditor()
                            ->deletable()
                            ->preserveFilenames()
                            ->visible(fn ($get) => in_array('alternate_images', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('alternate_images', $get('fields_to_update') ?? [])),

                        Forms\Components\Select::make('connection_type')
                            ->options([
                                'None' => 'None',
                                'Wi-Fi' => 'Wi-Fi',
                                'Ethernet' => 'Ethernet',
                                'Both Wi-fi and Ethernet' => 'Both Wi-fi and Ethernet',
                                'Fiber Optic' => 'Fiber Optic',
                                'Cellular' => 'Cellular',
                                'Bluetooth' => 'Bluetooth',
                                'Satellite' => 'Satellite',
                                'DSL' => 'DSL',
                                'Cable' => 'Cable',
                            ])
                            ->visible(fn ($get) => in_array('connection_type', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('connection_type', $get('fields_to_update') ?? [])),

                        Forms\Components\Select::make('facility_type')
                            ->options([
                                'Room' => 'Room',
                                'Office' => 'Office',
                                'Computer Laboratory' => 'Computer Laboratory',
                                'Incubation Hub' => 'Incubation Hub',
                                'Robotic Hub' => 'Robotic Hub',
                                'Hall' => 'Hall',
                            ])
                            ->visible(fn ($get) => in_array('facility_type', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('facility_type', $get('fields_to_update') ?? [])),

                        Forms\Components\Select::make('cooling_tools')
                                ->options([
                                    'None' => 'None',
                                    'Aircon' => 'Aircon',
                                    'Ceiling Fan' => 'Ceiling Fan',
                                    'Both Aircon and Ceiling Fan' => 'Both Aircon and Ceiling Fan',
                                ])
                            ->visible(fn ($get) => in_array('cooling_tools', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('cooling_tools', $get('fields_to_update') ?? [])),

                        Forms\Components\Select::make('floor_level')
                                ->options([
                                    '1st Floor' => '1st Floor',
                                    '2nd Floor' => '2nd Floor',
                                    '3rd Floor' => '3rd Floor',
                                    '4th Floor' => '4th Floor',
                                ])
                            ->visible(fn ($get) => in_array('floor_level', $get('fields_to_update') ?? [])) 
                            ->required(fn ($get) => in_array('floor_level', $get('fields_to_update') ?? [])),

                        Forms\Components\TextInput::make('building')
                                ->required()
                                ->default('HIRAYA')
                                ->visible(fn ($get) => in_array('building', $get('fields_to_update') ?? [])) 
                                ->required(fn ($get) => in_array('building', $get('fields_to_update') ?? [])),

                        Forms\Components\RichEditor::make('remarks')
                                ->placeholder('Anything that describes the facility (e.g., Computer Laboratory with space for 30 students)')
                                ->disableToolbarButtons(['attachFiles'])
                                ->visible(fn ($get) => in_array('remarks', $get('fields_to_update') ?? [])) 
                                ->required(fn ($get) => in_array('remarks', $get('fields_to_update') ?? [])),
                        ]);
                    })
                    ->action(function (array $data, $records) {
                        foreach ($records as $record) {
                            $updateData = [];

                            if (in_array('main_image', $data['fields_to_update'])) {
                                $updateData['main_image'] = $data['main_image'];
                            }
                            if (in_array('alternate_images', $data['fields_to_update'])) {
                                $updateData['alternate_images'] = $data['alternate_images'];
                            }
                            if (in_array('connection_type', $data['fields_to_update'])) {
                                $updateData['connection_type'] = $data['connection_type'];
                            }
                            if (in_array('facility_type', $data['fields_to_update'])) {
                                $updateData['facility_type'] = $data['facility_type'];
                            }
                            if (in_array('cooling_tools', $data['fields_to_update'])) {
                                $updateData['cooling_tools'] = $data['cooling_tools'];
                            }
                            if (in_array('floor_level', $data['fields_to_update'])) {
                                $updateData['floor_level'] = $data['floor_level'];
                            }
                            if (in_array('building', $data['fields_to_update'])) {
                                $updateData['building'] = $data['building'];
                            }
                            if (in_array('remarks', $data['fields_to_update'])) {
                                $updateData['remarks'] = $data['remarks'];
                            }
                            

                            $record->update($updateData);

                            // Insert into Equipment Monitoring
                            \App\Models\FacilityMonitoring::create([
                                'facility_id' => $record->id,
                                'monitored_by' => auth()->user()->id,
                                'remarks' => $updateData['remarks'] ?? $record->remarks,
                                'created_at' => now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
                            ]);

                        }
                
                        \Filament\Notifications\Notification::make()
                            ->title('Facilities updated successfully!')
                            ->success()
                            ->send();
                    }),
            Tables\Actions\BulkAction::make('add_to_borrowed_items')
                ->label('Request')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Confirm Facility Request')
                ->modalDescription('Please confirm to request of the selected facility.')
                ->form([
                    Forms\Components\Grid::make(['default' => 1])->schema([
                        Forms\Components\TextInput::make('borrowed_by')
                            ->required()
                            ->label('Name')
                            ->placeholder('Enter your name'),
                        Forms\Components\TextInput::make('phone_number')
                            ->required()
                            ->label('Phone Number')
                            ->maxLength(15),
                        Forms\Components\TextArea::make('college_department')
                            ->required()
                            ->label('College/Department')
                            ->placeholder('Enter your department'),
                        Forms\Components\DateTimePicker::make('expected_return_date')
                            ->required()
                            ->label('Expected Return Date')
                            ->default(now('Asia/Manila'))
                            ->timezone('Asia/Manila'),
                        Forms\Components\DateTimePicker::make('start_date_and_time_of_use')
                            ->required()
                            ->label('Start Date and Time of Use')
                            ->default(now('Asia/Manila'))
                            ->timezone('Asia/Manila'),
                        Forms\Components\DateTimePicker::make('end_date_and_time_of_use')
                            ->required()
                            ->label('End Date and Time of Use')
                            ->default(now('Asia/Manila'))
                            ->timezone('Asia/Manila'),
                        Forms\Components\TextArea::make('purpose')
                            ->required()
                            ->label('Purpose'),
                        Forms\Components\TextArea::make('remarks')
                            ->label('Remarks'),
                    ]),
                ])
                ->action(function ($data, $records) {
                    $successfulEntries = false;

                     // Generate unique request code
                $latestRecord = BorrowedItems::latest()->first();
                $requestCode = $latestRecord
                    ? str_pad((int)substr($latestRecord->request_code, 1) + 1, 5, '0', STR_PAD_LEFT)
                    : '00001';

                  // Loop through each selected equipment record
                  foreach ($records as $record) {
                    $facilityId = $record->id;

                    BorrowedItems::create([
                        'user_id' => auth()->id(),
                        'borrowed_by' => $data['borrowed_by'],
                        'phone_number' => $data['phone_number'],
                        'college_department' => $data['college_department'],
                        'expected_return_date' => $data['expected_return_date'],
                        'start_date_and_time_of_use' => $data['start_date_and_time_of_use'],
                        'end_date_and_time_of_use' => $data['end_date_and_time_of_use'],
                        'purpose' => $data['purpose'],
                        'remarks' => $data['remarks'],
                        'request_code' => $requestCode,
                        'facility_id' => $facilityId,
                        'request_status' => 'Pending',
                        'status' => '------',
                    ]);
        
                    $successfulEntries = true;
                }

                 // Notify if borrow action was successful
                 if ($successfulEntries) {
                    Notification::make()
                        ->success()
                        ->title(' Request Submitted')
                        ->body('The facility has been successfully added to your Requested/Borrowed items.')
                        ->send();
                }
            }),

        ];

        if (!$isFaculty) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
            ->description('To request a facility for use, select a facility. An "Actions" button will appear. Click it and choose "Add to Request List".
            For more information, go to the dashboard to download the user manual.')
            ->query(Facility::with('user'))
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->stacked(),
                Tables\Columns\ImageColumn::make('alternate_images')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->stacked(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('connection_type')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility_type')
                    ->label('Facility Type')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('floor_level')
                    ->label('Floor Level')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cooling_tools')
                    ->label('Cooling Tools')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('building')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn (string $state): string => strip_tags($state))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = strip_tags($column->getState());
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                    
                    
                    ->html(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->format('F j, Y h:i A') : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => route('facility-monitoring-page', ['facility' => $record->id]))
            ->openRecordUrlInNewTab()
            ->defaultSort('created_at', 'desc')
            

            ->filters([
                SelectFilter::make('floor_level')
                    ->label('Floor Level')
                    ->options(
                        Facility::query()
                            ->whereNotNull('floor_level') // Filter out null values
                            ->pluck('floor_level', 'floor_level')
                            ->toArray()
                    ),
                    SelectFilter::make('facility_type')
                    ->label('Facility Type')
                    ->options(
                        Facility::query()
                            ->whereNotNull('facility_type') // Filter out null values
                            ->pluck('facility_type', 'facility_type')
                            ->toArray()
                    ),
                    SelectFilter::make('connection_type')
                    ->label('Connection Type')
                    ->options(
                        Facility::query()
                            ->whereNotNull('connection_type') // Filter out null values
                            ->pluck('connection_type', 'connection_type')
                            ->toArray()
                    ),
                    SelectFilter::make('cooling_tools')
                    ->label('Cooling Tools')
                    ->options(
                        Facility::query()
                            ->whereNotNull('cooling_tools') // Filter out null values
                            ->pluck('cooling_tools', 'cooling_tools')
                            ->toArray()
                    ),
                    SelectFilter::make('created_at')
                ->label('Created At')
                ->options(
                    Facility::query()
                        ->whereNotNull('created_at') // Filter out null values
                        ->get(['created_at']) 
                        ->mapWithKeys(function ($user) {
                            $date = $user->created_at; 
                            $formattedDate = \Carbon\Carbon::parse($date)->format('F j, Y');
                            return [$date->toDateString() => $formattedDate]; 
                        })
                        ->toArray()
                ),
            ])
            ->actions([
                    Tables\Actions\ViewAction::make('view')
                    ->label('')
                    ->tooltip('View Facility')
                    ->url(fn (Facility $record) => route('facility-monitoring-page', ['facility' => $record->id]))
                    ->openUrlInNewTab(),
                    Tables\Actions\Action::make('viewFacilityEquipment')
                        ->label('')
                        ->icon('heroicon-o-cog')
                        ->color('info')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->slideOver()
                        ->modalHeading('Equipment List')
                        ->tooltip('View Facility Equipment')
                        ->modalContent(function ($record) {
                            $equipment = Equipment::where('facility_id', $record->id)->paginate(100);
                            return view('filament.resources.facility-equipment-modal', [
                                'equipment' => $equipment,
                            ]);
                        }),
                    Tables\Actions\EditAction::make()
                        ->label('')
                        ->tooltip('Edit Facility'),
                    Tables\Actions\DeleteAction::make()
                        ->label('')
                        ->tooltip('Delete Facility'),
                   


                    Tables\Actions\ActionGroup::make([

    
                    ],)
                ],  position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions'),
            ]);
    }
   

    public static function create(array $data)
    {
        if (Facility::where('name', $data['name'])->exists()) {
            Notification::make()
                ->title('Duplicate Facility')
                ->body('A facility with this name already exists.')
                ->danger()
                ->send();
            return;
        }

        Facility::create($data);

        Notification::make()
            ->title('Facility Created')
            ->body('The facility has been successfully created.')
            ->success()
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
            'view' => Pages\ViewFacility::route('/{record}'),

        ];
    }
}
