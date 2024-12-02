<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use App\Models\Equipment;
use App\Models\FacilityMonitoring;
use App\Models\User;
use App\Models\RequestList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;


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
                Section::make('Facility Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->placeholder('Facility Name Displayed On The Door (e.g., CL1, CL2)')
                                    ->required()
                                    ->unique('facilities','name')
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
                Section::make('Facility Image')
                    ->schema([
                        Forms\Components\FileUpload::make('facility_img')
                            ->image()
                            ->label('Facility Image')
                            //->required()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('facility'),
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
            Tables\Actions\BulkAction::make('add_to_Request_list')
                ->label('Add to Request List')
                ->icon('heroicon-o-shopping-cart')
                ->action(function (Collection $records) {
                    foreach ($records as $record) {
                        RequestList::updateOrCreate(
                            [
                                'user_id' => auth()->id(),
                                'facility_id' => $record->id,
                            ]
                        );
                    }

                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Selected facilities have been added to your request list.')
                        ->send();
                })
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Add to Request List')
                ->modalDescription('Confirm to add selected facilities to your request list'),
        ];

        if (!$isFaculty) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
            ->description('To request a facility for use, select a facility. An "Actions" button will appear. Click it and choose "Add to Request List".
            For more information, go to the dashboard to download the user manual.')
            ->query(Facility::with('user'))
            ->columns([
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
                    ->html(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return $state ? $state->format('F j, Y h:i A') : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
                        ->get(['created_at']) // Fetch the 'created_at' values
                        ->mapWithKeys(function ($user) {
                            $date = $user->created_at; // Access the created_at field
                            $formattedDate = \Carbon\Carbon::parse($date)->format('F j, Y');
                            return [$date->toDateString() => $formattedDate]; // Use string representation as key
                        })
                        ->toArray()
                ),
            ])
            ->actions([
                      
                    
                    Tables\Actions\ViewAction::make('viewFacilityEquipment')
                        ->label('View Facility Equipment')
                        ->icon('heroicon-o-cog')
                        ->color('success')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->slideOver()
                        ->modalHeading('Equipment List')
                        ->modalContent(function ($record) {
                            $equipment = Equipment::where('facility_id', $record->id)->paginate(100);
                            return view('filament.resources.facility-equipment-modal', [
                                'equipment' => $equipment,
                            ]);
                        }),
                    /*Tables\Actions\ViewAction::make('view_monitoring')
                        ->label('View Facility Records')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->color('info')
                        ->modalHeading('Monitoring Records')
                        ->modalContent(function ($record) {
                            $facilityId = $record->id;
                            $monitorings = FacilityMonitoring::where('facility_id', $facilityId)->with('user')->get();
                            return view('filament.resources.facility-monitoring-modal', [
                                'monitorings' => $monitorings,
                            ]);
                        }),*/
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\ActionGroup::make([
                    //Tables\Actions\EditAction::make()->color('warning'),

                    Tables\Actions\Action::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-plus')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-check')
                        ->modalHeading('Add to Monitoring')
                        ->modalDescription('Confirm to add selected facility to your Monitoring')
                        ->form(function (Forms\Form $form, $record) {
                            return $form->schema([
                                Forms\Components\Select::make('monitored_by')
                                    ->label('Monitored By')
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->default(auth()->user()->id)
                                    ->disabled()
                                    ->required(),
                                Forms\Components\DatePicker::make('monitored_date')
                                    ->label('Monitoring Date')
                                    ->required()
                                    ->default(now())
                                    ->disabled()
                                    ->format('Y-m-d'),
                                Forms\Components\TextInput::make('remarks')
                                    ->default($record->remarks)
                                    ->formatStateUsing(fn($state) => strip_tags($state))
                                    ->label('Remarks'),
                            ]);
                        })
                        ->action(function (array $data, $record) {
                            $data['facility_id'] = $record->id;

                            if (empty($data['monitored_by'])) {
                                $data['monitored_by'] = auth()->user()->id;
                            }

                            if (empty($data['monitored_date'])) {
                                $data['monitored_date'] = now()->format('Y-m-d');
                            }

                            FacilityMonitoring::create($data);

                            Facility::where('id', $record->id)
                                ->update(['remarks' => $data['remarks']]);

                            Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Selected facility have been added to your monitoring.')
                                ->send();
                        })
                        ->hidden(fn () => $isFaculty),
                        
                ])
            ])
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
           // 'view' => Pages\ViewFacility::route('/{record}'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
