<?php

namespace App\Filament\Resources;

//use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\Category;
use App\Models\User;
use App\Models\EquipmentMonitoring;
//use App\Models\BorrowList;
use App\Models\RequestList;
use App\Models\StockUnit;
use App\Models\BorrowedItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\TextInput;
use App\Rules\UniquePropertyCategoryEquipment;




class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    // protected static ?string $navigationGroup = 'Equipment';
    protected static ?string $label = 'Equipment';
    protected static ?string $navigationLabel = 'Equipment';

    public static ?string $slug = 'equipment';

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string  $recordTitleAttribute = 'description';

 
    
    public function query(): Builder
    {
        return Equipment::with('stockUnit');
    }
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        \Log::info($record);
        
        return [
            'PO Number' => $record->po_number ?? 'Unknown', 
            'Unit No.' => $record->unit_no ?? 'Unknown', 
            'Brand Name' => $record->brand_name ?? 'Unknown',
            'Description' => $record->description ?? 'Unknown', 
            'Category' => $record->category->description ?? 'N/A', 
            'Facility' => $record->facility->name ?? 'N/A',
            'Serial No.' => $record->serial_no ?? 'N/A', 
            'Control No.' => $record->control_no ?? 'N/A', 
            'Property No.' => $record->property_no ?? 'N/A', 
            'Person Liable' => $record->person_liable ?? 'N/A', 
            'Date Acquired' => $record->date_acquired ?? 'N/A', 
            'Remarks' => $record->remarks ?? 'N/A', 
            
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return['po_number','brand_name','description','serial_no','category.description','facility.name',
        'serial_no','control_no','property_no','person_liable','date_acquired','remarks'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Equipment Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([

                               
                                Forms\Components\TextInput::make('po_number')
                                    ->placeholder('Refer to the inventory sticker.')
                                    ->label('PO Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('unit_no')
                                    ->placeholder('Set number pasted on the Comlab table.')
                                    ->label('Unit Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('brand_name')
                                    ->placeholder('Brand Name of Equipment')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('description')
                                    ->placeholder('Specifications, e.g., dimensions, weight, power'),
                                    
                                Forms\Components\Select::make('facility_id')
                                    ->relationship('facility', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                        ->label('Create Facility')
                                        ->placeholder('Facility Name Displayed On The Door (e.g., CL1, CL2)')
                                        ->required()
                                        ->maxLength(255)
                                    ]),
                                       
                                   
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'description')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('description')
                                        ->label('Create Category')
                                        ->placeholder('E.g., Monitor, System Unit, AVR/UPS, etc.')
                                        ->required()
                                        ->maxLength(255)
                                       
                                    ]),
                                    
                                    
                                 
                                    
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Working' => 'Working',
                                        'For Repair' => 'For Repair',
                                        'For Replacement' => 'For Replacement',
                                        'Lost' => 'Lost',
                                        'For Disposal' => 'For Disposal',
                                        'Disposed' => 'Disposed',
                                    ])
                                    ->native(false)
                                    ->required(),
                               
                                 
                                Forms\Components\TextInput::make('date_acquired')
                                    ->label('Date Acquired')
                                    ->placeholder('mm-dd-yy. E.g., 01-28-24')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('supplier')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('amount')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('estimated_life')
                                    ->label('Estimated Life')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('item_no')
                                    ->label('Item Number')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                               
                                Forms\Components\TextInput::make('property_no')
                                    ->label('Property Number')
                                    ->placeholder('Refer to the Equipment sticker.'),
                                    /*->rules([
                                        Rule::unique('equipment') // Validate unique 'property_no' in the 'equipment' table
                                            ->where(function ($query) {
                                                return $query->where('category_id', request()->input('category_id'));
                                            })
                                            ->ignore(request()->route('equipment')) // Ignore the current record being updated (if updating)
                                    ])
                                    ->validationMessages([
                                        'unique' => 'This property number with the same category already exists.',
                                    ]),*/
                                    
                                    
                                Forms\Components\TextInput::make('control_no')
                                    ->label('Control Number')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('serial_no')
                                    ->label('Serial Number')
                                    ->placeholder('Refer to the Equipment sticker.'),
                                   
                                    
                                /*Forms\Components\Select::make('no_of_stocks')
                                    ->label('No. of Stocks')
                                    ->options(array_combine(range(1, 1000), range(1, 1000))),
                                Forms\Components\Select::make('stock_unit_id')
                                    ->label('Stock Unit')
                                    ->relationship('stockUnit', 'description')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('description')
                                        ->label('Create Stock Unit')
                                        ->required()
                                        ->maxLength(255),
                                    ]),
                                       
                                Forms\Components\Select::make('restocking_point')
                                    ->label('Restocking Point')
                                    ->options(array_combine(range(1, 1000), range(1, 1000))),*/
                                Forms\Components\TextInput::make('person_liable')
                                    ->label('Person Liable')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('remarks')
                                    ->placeholder('Anything that describes the Equipment.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');
         
       
        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            //Tables\Actions\EditBulkAction::make(),
            Tables\Actions\BulkAction::make('add_to_request_list')
                ->label('Add to Request List')
                ->icon('heroicon-o-shopping-cart')
                ->action(function (Collection $records) {
                    $added = false; // Flag to track if any items were successfully added
                    $unreturnedItems = []; // Array to track unreturned items
                    $nonWorkingItems = []; // Array to track non-working items
                
                    foreach ($records as $record) {
                        // Get the equipment ID
                        $equipmentId = $record->id;
                
                        // Check if the equipment is currently borrowed and has a status of "unreturned"
                        $borrowedItem = BorrowedItems::where('equipment_id', $equipmentId)
                            ->where('status', 'unreturned')
                            ->first();
                
                        if ($borrowedItem) {
                            // Track unreturned items
                            $unreturnedItems[] = $record->brand_name;  // Assuming 'brand_name' is a field on the equipment record
                            continue; // Skip this record and proceed with the next one
                        }
                
                        // Check if the equipment status is "working"
                        if  (strtolower($record->status) !== 'working'){
                            // Track non-working items
                            $nonWorkingItems[] = $record->brand_name;  // Assuming 'brand_name' is a field on the equipment record
                            continue; // Skip this record if not working
                        }
                        
                        // If the equipment is not unreturned and is working, add it to the request list
                        $categoryId = $record->category_id; 
                        $facilityId = $record->facility_id; 
                
                        RequestList::updateOrCreate(
                            [
                                'user_id' => auth()->id(),
                                'equipment_id' => $equipmentId,
                                'facility_id' => $facilityId ?? null,
                            ]
                        );
                
                        $added = true; // Mark that we added at least one item
                    }
                
                    // Notify for unreturned items first
                    if (count($unreturnedItems) > 0) {
                        Notification::make()
                            ->warning()
                            ->title('Cannot be Added')
                            ->body(implode(', ', $unreturnedItems) . ' are unreturned and cannot be added to the request list.')
                            ->send();
                    }
                
                    // Notify for non-working items
                    if (count($nonWorkingItems) > 0) {
                        Notification::make()
                            ->warning()
                            ->title('Cannot be Added')
                            ->body(implode(', ', $nonWorkingItems) . ' are not working and cannot be added to the request list.')
                            ->send();
                    }
                
                    // Only send success notification if any item was successfully added
                    if ($added) {
                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body('Selected items have been added to your request list.')
                            ->send();
                    }
                })
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Add to Request List')
                ->modalDescription('Confirm to add selected items to your request list'),
                
        
        ];
        
        // Conditionally add ExportBulkAction
        if (!$isFaculty) {
            //$bulkActions[] = Tables\Actions\DeleteBulkAction::make();
            $bulkActions[] = ExportBulkAction::make();
        }
        
    
       
        return $table
            ->description('To borrow, select an equipment. An "Actions" button will appear. Click it and choose "Add to Request List". 
           For more information, go to the dashboard to download the user manual.')
            ->columns([
                Tables\Columns\TextColumn::make('po_number')
                    ->label('PO Number')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit Number')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('brand_name')
                    ->label('Brand Name')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('facility.name')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('category.description')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'working' => 'success',
                        'for repair' => 'warning',
                        'for replacement' => 'primary',
                        'lost' => 'danger',
                        'for disposal' => 'primary',
                        'disposed' => 'danger',
                        default => 'secondary',  

                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('date_acquired')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estimated_life')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item_no')
                    ->label('Item Number')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('property_no')
                    ->searchable()
                    ->label('Property Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('control_no')
                    ->searchable()
                    ->label('Control Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('serial_no')
                    ->searchable()
                    ->label('Serial Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    }),
                /*Tables\Columns\TextColumn::make('no_of_stocks')
                    ->label('No. of Stocks')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->no_of_stocks} {$stockUnitDescription}";
                    })
                    ->toggleable(isToggledHiddenByDefault: true),*/
                /*Tables\Columns\TextColumn::make('stockUnit.description')
                    ->label("Stock Unit")
                    ->searchable()
                    ->sortable(),
                    //->toggleable(isToggledHiddenByDefault: true),*/
                /*Tables\Columns\TextColumn::make('restocking_point')
                    ->searchable()
                    ->sortable()

                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->restocking_point} {$stockUnitDescription}";
                    })                    
                    ->toggleable(isToggledHiddenByDefault: true),*/
                Tables\Columns\TextColumn::make('person_liable')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                 /*Tables\Columns\TextColumn::make('user.name')
                 ->LABEL('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
                
                ])

            
                ->filters([
                   
                    SelectFilter::make('po_number')
                    ->label('PO Number')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('po_number') // Filter out null values
                            ->pluck('po_number', 'po_number')
                            ->toArray()
                    ),
                    SelectFilter::make('brand_name')
                    ->label('Brand Name')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('brand_name') // Filter out null values
                            ->pluck('brand_name', 'brand_name')
                            ->toArray()
                    ),
                    SelectFilter::make('Category')
                    ->relationship('category','description'),
                    
                    //->searchable ()
                    SelectFilter::make('Facility')
                    ->relationship('facility','name'),
                    SelectFilter::make('person_liable')
                    ->label('Person Liable')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('person_liable') // Filter out null values
                            ->pluck('person_liable', 'person_liable')
                            ->toArray()
                    ),
                    SelectFilter::make('unit_no')
                    ->label('Unit No.')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('unit_no') // Filter out null values
                            ->pluck('unit_no', 'unit_no')
                            ->toArray()
                    ),
                    SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('status') // Filter out null values
                            ->pluck('status', 'status')
                            ->toArray()
                    ),
                    SelectFilter::make('date_acquired')
                    ->label('Date Aquired')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('date_acquired') // Filter out null values
                            ->pluck('date_acquired', 'date_acquired')
                            ->toArray()
                    ),
                    SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('supplier') // Filter out null values
                            ->pluck('supplier', 'supplier')
                            ->toArray()
                    ),
                    SelectFilter::make('description')
                    ->label('Description')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('description') // Filter out null values
                            ->pluck('description', 'description')
                            ->toArray()
                    ), 
                        /*SelectFilter::make('created_at')
                    ->label('Created At')
                    ->options(
                        Category::query()
                            ->whereNotNull('created_at') // Filter out null values
                            ->get(['created_at']) // Fetch the 'created_at' values
                            ->mapWithKeys(function ($user) {
                                $date = $user->created_at; // Access the created_at field
                                $formattedDate = \Carbon\Carbon::parse($date)->format('F j, Y');
                                return [$date->toDateString() => $formattedDate]; // Use string representation as key
                            })
                            ->toArray()
                    ),*/
                    
                ])
                /*->recordUrl(function ($record) {
                    return Pages\ViewEquipment::getUrl([$record->id]);
                })*/
                ->actions([
                    /*Tables\Actions\ViewAction::make('view_monitoring')
                    ->label('View Equipment Records')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->color('info')
                    ->modalHeading('Monitoring Records')
                    ->modalContent(function ($record) {
                        $equipmentId = $record->id;
                        $monitorings = EquipmentMonitoring::with('equipment.facility', 'user')
                            ->where('equipment_id', $equipmentId)
                            ->get();
                        return view('filament.resources.equipment-monitoring-modal', [
                            'monitorings' => $monitorings,
                        ]);
                    }),*/
                    
                    Tables\Actions\EditAction::make(),
                    
                
                    Tables\Actions\ActionGroup::make([
                       // ListPreviewAction::make(),
                        
                       

                            Tables\Actions\Action::make('Update Status')
                            ->icon('heroicon-o-plus')
                            ->color('primary')
                            ->requiresConfirmation()
                            ->modalIcon('heroicon-o-check')
                            ->modalHeading('Update Equipment Status')
                            ->modalDescription('Confirm to update equipment status')
                            ->form(function (Forms\Form $form, $record) {
                                return $form
                                    ->schema([
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
                
                                        Forms\Components\Select::make('status')
                                            ->required()
                                            ->options([
                                                'Working' => 'Working',
                                                'For Repair' => 'For Repair',
                                                'For Replacement' => 'For Replacement',
                                                'Lost' => 'Lost',
                                                'For Disposal' => 'For Disposal',
                                                'Disposed' => 'Disposed',
                                                'Borrowed' => 'Borrowed',
                                            ])
                                            ->default($record->status)
                                            ->native(false),
                                        Forms\Components\Select::make('facility_id')
                                            ->label ('New Assigned Facility')
                                            ->relationship('facility', 'name')
                                            ->default($record->facility_id)
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('remarks')
                                            ->default($record->remarks)
                                            ->formatStateUsing(fn($state) => strip_tags($state))
                                            ->label('Remarks'),
                                    ]);
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
                                        ->format('Y-m-d'),
                                    Forms\Components\Select::make('status')
                                        ->required()
                                        ->options([
                                            'Working' => 'Working',
                                            'For Repair' => 'For Repair',
                                            'For Replacement' => 'For Replacement',
                                            'Lost' => 'Lost',
                                            'For Disposal' => 'For Disposal',
                                            'Disposed' => 'Disposed',
                                            'Borrowed' => 'Borrowed',
                                        ])
                                        ->default($record->status)
                                        ->native(false),
                                    Forms\Components\Select::make('facility_id')
                                        ->relationship('facility', 'name')
                                        ->default($record->facility_id)
                                        ->required(),
                                    Forms\Components\TextInput::make('remarks')
                                        ->default($record->remarks)
                                        ->formatStateUsing(fn($state) => strip_tags($state))
                                        ->label('Remarks'),
                                ]);
                            })
                            ->action(function (array $data, $record) {
                                $data['equipment_id'] = $record->id;
                
                                if (empty($data['monitored_by'])) {
                                    $data['monitored_by'] = auth()->user()->id;
                                }
                
                                if (empty($data['monitored_date'])) {
                                    $data['monitored_date'] = now()->format('Y-m-d');
                                }
                
                                EquipmentMonitoring::create($data);
                
                                $record->update([
                                    'status' => $data['status'],
                                    'facility_id' => $data['facility_id'],
                                    'remarks' => $data['remarks'],
                                ]);
                
                                Notification::make()
                                    ->success()
                                    ->title('Success')
                                    ->body('Status of the selected item/s have been updated.')
                                    ->send();
                            })
                       
                            ->hidden(fn () => $isFaculty),

                    ]),
                ])
                ->bulkActions([

                    Tables\Actions\BulkActionGroup::make($bulkActions)
                        ->label('Actions')
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            //'view' => Pages\ViewEquipment::route('/{record}'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
