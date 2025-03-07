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
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\View;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\TextInput;
use App\Rules\UniquePropertyCategoryEquipment;
use LaraZeus\Qr\Facades\Qr;
use App\Filament\Resources\EquipmentResource\Pages\ViewQrCode;
use Filament\Tables\Enums\ActionsPosition;








class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    // protected static ?string $navigationGroup = 'Equipment';
    protected static ?string $label = 'Equipment';
    protected static ?string $navigationLabel = 'Equipment';

    public static ?string $slug = 'equipment';
   // protected ?string $maxContentWidth = 'full';

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
                Section::make('Equipment Image')
                ->schema([
                    Forms\Components\FileUpload::make('main_image')
                    ->imageEditor()
                    ->deletable()
                    ->preserveFilenames(),
                    
                    Forms\Components\FileUpload::make('alternate_images')
                    ->imageEditor()
                    ->deletable()
                    ->multiple()
                    ->preserveFilenames(),

                    

                    \LaraZeus\Qr\Components\Qr::make('qr_code')
                    // ->asSlideOver()
                    ->optionsColumn('qr_code')
                    ->actionIcon('heroicon-s-building-library'),
 
                    ])
                    ->columnSpan(3)
                    ->columns(3)
                    ->collapsible(),
                   
                    
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
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->unique(
                                        table: 'equipment', 
                                        column: 'serial_no', 
                                        ignoreRecord: true
                                    )
                                    ->validationMessages([
                                        'unique' => 'This serial number already exists.',
                                    ]),
                               
                                Forms\Components\Select::make('person_liable')
                                    ->relationship('user', 'name')
                                    ->createOptionForm([
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
                                        ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                                        // ->visible(fn ($livewire) =>$livewire instanceof Pages\CreateUser),
                                        Forms\Components\TextInput::make('password_confirmation')
                                        ->password()
                                        //->same('password')                          
                                        ->requiredWith('password')
                                        ->revealable()
                                        ->visible(fn ($livewire) =>$livewire instanceof Pages\CreateUser),
                                
                                    ]),
                                 
                                   
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
            Tables\Actions\BulkAction::make('bulk_update')
            ->label('Bulk Update')
            ->icon('entypo-cycle')
            ->color('warning')
            ->requiresConfirmation()
            ->modalIcon('entypo-cycle')
            ->modalHeading('Bulk Update Equipment Details')
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
                    Forms\Components\CheckboxList::make('fields_to_update')
                        ->label('Select fields to update.')
                        ->options([
                            'status' => 'Status',
                            'facility_id' => 'Facility',
                            'category_id' => 'Category',
                            'main_image' => 'Main Image',
                            'alternate_images'=> 'Alternate Images',
                            'brand_name' => 'Brand Name',
                            'description' => 'Description',
                            'date_acquired' => 'Date Acquired',
                            'supplier' => 'Supplier',
                            'amount' => 'Amount',
                            'estimated_life' => 'Estimated Life',
                            'po_number' => 'PO No.',
                            'unit_no' => 'Unit No.',
                            'item_no' => 'Item No.',
                            'property_no' => 'Property No.',
                            'control_no' => 'Control No.',
                            'property_no' => 'Property No.',
                            'serial_no' => 'Serial No.',
                            'person_liable' => 'Person Liable',
                            'remarks' => 'Remarks',
                        ])
                        ->columns(2)
                        ->reactive(), 
        
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'working' => 'Working',
                            'for repair' => 'For Repair',
                            'for replacement' => 'For Replacement',
                            'lost' => 'Lost',
                            'for disposal' => 'For Disposal',
                            'disposed' => 'Disposed',
                           
                        ])
                        ->visible(fn ($get) => in_array('status', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('status', $get('fields_to_update') ?? [])),
        
                    Forms\Components\Select::make('facility_id')
                        ->label('Facility')
                        ->options(\App\Models\Facility::all()->pluck('name', 'id'))
                        ->visible(fn ($get) => in_array('facility_id', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('facility_id', $get('fields_to_update') ?? [])),
        
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(\App\Models\Category::all()->pluck('description', 'id'))
                        ->visible(fn ($get) => in_array('category_id', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('category_id', $get('fields_to_update') ?? [])),

                    Forms\Components\FileUpload::make('main_image')
                        ->label('Main Image')
                        ->visible(fn ($get) => in_array('main_image', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('main_image', $get('fields_to_update') ?? [])),

                    Forms\Components\FileUpload::make('alternate_images')
                        ->imageEditor()
                        ->deletable()
                        ->multiple()
                        ->preserveFilenames()
                        ->label('Alternate Images')
                        ->visible(fn ($get) => in_array('alternate_images', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('alternate_images', $get('fields_to_update') ?? [])),
                    
                    Forms\Components\TextInput::make('brand_name')
                        ->label('Brand Name')
                        ->visible(fn ($get) => in_array('brand_name', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('brand_name', $get('fields_to_update') ?? [])),
                    
                    Forms\Components\TextInput::make('description')
                        ->placeholder('Specifications, e.g., dimensions, weight, power')
                        ->label('Description')
                        ->visible(fn ($get) => in_array('description', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('description', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('date_acquired')
                        ->label('Date Acquired')
                        ->visible(fn ($get) => in_array('date_acquired', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('date_acquired', $get('fields_to_update') ?? [])),
                    
                    Forms\Components\TextInput::make('supplier')
                        ->label('Supplier')
                        ->visible(fn ($get) => in_array('supplier', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('supplier', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->prefix('₱')
                        ->visible(fn ($get) => in_array('amount', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('amount', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('estimated_life')
                        ->label('Estimated Life')
                        ->visible(fn ($get) => in_array('estimated_life', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('estimated_life', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('po_number')
                        ->label('PO Number')
                        ->visible(fn ($get) => in_array('po_number', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('po_number', $get('fields_to_update') ?? [])),
                        
                    Forms\Components\TextInput::make('unit_no')
                        ->label('Unit No.')
                        ->visible(fn ($get) => in_array('unit_no', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('unit_no', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('item_no')
                        ->label('Item No.')
                        ->visible(fn ($get) => in_array('item_no', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('item_no', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('control_no')
                        ->label('Control No.')
                        ->visible(fn ($get) => in_array('control_no', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('control_no', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('property_no')
                        ->label('Property No.')
                        ->visible(fn ($get) => in_array('property_no', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('property_no', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('serial_no')
                        ->label('Serial No.')
                        ->visible(fn ($get) => in_array('serial_no', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('serial_no', $get('fields_to_update') ?? [])),

                    Forms\Components\Select::make('person_liable')
                        ->relationship('user', 'name')
                        ->visible(fn ($get) => in_array('person_liable', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('person_liable', $get('fields_to_update') ?? [])),
                        
                    Forms\Components\Textarea::make('remarks')
                        ->label('Remarks')
                        ->rows(3)
                        ->visible(fn ($get) => in_array('remarks', $get('fields_to_update') ?? []))
                        ->required(fn ($get) => in_array('remarks', $get('fields_to_update') ?? [])),
                ]);
            })
            ->action(function (array $data, $records) {
                foreach ($records as $record) {
                    $updateData = [];
        
                    if (in_array('status', $data['fields_to_update'])) {
                        $updateData['status'] = $data['status'];
                    }
                    if (in_array('facility_id', $data['fields_to_update'])) {
                        $updateData['facility_id'] = $data['facility_id'];
                    }
                    if (in_array('category_id', $data['fields_to_update'])) {
                        $updateData['category_id'] = $data['category_id'];
                    }
                    if (in_array('main_image', $data['fields_to_update'])) {
                        $updateData['main_image'] = $data['main_image'];
                    }
                    if (in_array('alternate_images', $data['fields_to_update'])) {
                        $updateData['alternate_images'] = $data['alternate_images'];
                    }
                    if (in_array('brand_name', $data['fields_to_update'])) {
                        $updateData['brand_name'] = $data['brand_name'];
                    }
                    if (in_array('description', $data['fields_to_update'])) {
                        $updateData['description'] = $data['description'];
                    }
                    if (in_array('date_acquired', $data['fields_to_update'])) {
                        $updateData['date_acquired'] = $data['date_acquired'];
                    }
                    if (in_array('supplier', $data['fields_to_update'])) {
                        $updateData['supplier'] = $data['supplier'];
                    }
                    if (in_array('amount', $data['fields_to_update'])) {
                        $updateData['amount'] = $data['amount'];
                    }
                    if (in_array('estimated_life', $data['fields_to_update'])) {
                        $updateData['estimated_life'] = $data['estimated_life'];
                    }
                    if (in_array('po_number', $data['fields_to_update'])) {
                        $updateData['po_number'] = $data['po_number'];
                    }
                    if (in_array('unit_no', $data['fields_to_update'])) {
                        $updateData['unit_no'] = $data['unit_no'];
                    }
                    if (in_array('item_no', $data['fields_to_update'])) {
                        $updateData['item_no'] = $data['item_no'];
                    }
                    if (in_array('property_no', $data['fields_to_update'])) {
                        $updateData['property_no'] = $data['property_no'];
                    }
                    if (in_array('control_no', $data['fields_to_update'])) {
                        $updateData['control_no'] = $data['control_no'];
                    }
                    if (in_array('serial_no', $data['fields_to_update'])) {
                        $updateData['serial_no'] = $data['serial_no'];
                    }
                    if (in_array('person_liable', $data['fields_to_update'])) {
                        $updateData['person_liable'] = $data['person_liable'];
                    }
                    if (in_array('remarks', $data['fields_to_update'])) {
                        $updateData['remarks'] = $data['remarks'];
                    }
        
                    $record->update($updateData);

                    // Insert into Equipment Monitoring
                    \App\Models\EquipmentMonitoring::create([
                        'equipment_id' => $record->id,
                        'monitored_by' => auth()->user()->id,
                        'status' => $updateData['status'] ?? $record->status,
                        'facility_id' => $updateData['facility_id'] ?? $record->facility_id,
                        'remarks' => $updateData['remarks'] ?? $record->remarks,
                        'created_at' => now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s'),
                    ]);
                }
        
                \Filament\Notifications\Notification::make()
                    ->title('Equipment updated successfully!')
                    ->success()
                    ->send();
            }),
        
            Tables\Actions\BulkAction::make('add_to_request_list')
                ->label('Add to Request List')
                ->icon('heroicon-o-plus')
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
                Tables\Columns\ImageColumn::make('main_image')
                    ->stacked()
                    ->sortable(query: function ($query, $direction) {
                        $query->orderByRaw("ISNULL(main_image) $direction, main_image $direction");
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('alternate_images')
                    ->stacked()
                    ->sortable(query: function ($query, $direction) {
                        $query->orderByRaw("ISNULL(alternate_images) $direction, alternate_images $direction");
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('qr_code')
                    ->stacked()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('po_number')
                    ->label('PO Number')
                    ->searchable()
                    // ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    // ->extraAttributes(['class' => 'sticky left-0 bg-white z-10'])
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit Number')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->limit(9)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state))),
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
                    ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state)))
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
                    ->money('PHP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estimated_life')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item_no')
                    ->label('Item Number')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('property_no')
                    ->searchable()
                    ->label('Property Number')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('control_no')
                    ->searchable()
                    ->label('Control Number')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('serial_no')
                    ->searchable()
                    ->label('Serial Number')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Person Liable')
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
                    ->toggleable(isToggledHiddenByDefault: true),
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
                // ->recordUrl(fn ($record) => route('filament.resources.equipment-monitoring.modal', ['equipment' => $record->id]))
                // ->openRecordUrlInNewTab()
                ->defaultSort('created_at', 'desc')
                ->recordAction('view_equipment') 

            
                ->filters([
                    SelectFilter::make('main_image')
                    ->label('Main Image')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('main_image') // Filter out null values
                            ->pluck('main_image', 'main_image')
                            ->toArray()
                    ),
                   
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
                    // Tables\Actions\Action::make('View QR Code')
                    // ->label('View QR Code')
                    // ->icon('heroicon-s-qr-code')
                    // ->url(fn (Equipment $record) => route('filament.resources.equipment-resource.pages.view-qr-code', $record)),
                    
                    // Tables\Actions\ViewAction::make('view')
                    // ->url(fn (Equipment $record) => route('equipment-monitoring-page', ['equipment' => $record->id]))
                    // ->openUrlInNewTab(),
               
                    // Tables\Actions\Action::make('View QR Code')
                    // ->label('View QR Code')
                    // ->icon('heroicon-s-qr-code')
                    // ->modalHeading('QR Code')
                    // ->modalContent(function ($record) {
                    //     // Here, $record will automatically be passed into the closure
                    //     return view('filament.resources.equipment-resource.views.view-qr-code', [
                    //         'equipment' => $record,
                    //     ]);
                    // })
                    // ->action(function (Equipment $record) {
                    //     // You can add additional logic here if needed
                    // }),

                    Tables\Actions\Action::make('view_equipment')
                    ->label(' ')
                    ->tooltip('View Equipment Details and Monitoring')
                    ->icon('fas-eye')
                    ->color('info')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalHeading('')
                    ->modalContent(function ($record) {
                        $equipment = $record->load(['facility', 'category']); // Ensure related data is loaded
                        $monitorings = EquipmentMonitoring::with('facility', 'user')
                            ->where('equipment_id', $record->id)
                            ->get();
                
                        return view('filament.resources.equipment-monitoring-modal', [
                            'equipment' => $equipment,
                            'monitorings' => $monitorings,
                        ]);
                    }),

                    Tables\Actions\EditAction::make('edit_equipment')
                        ->label('')
                        ->tooltip('Edit Equipment')
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make('delete_equipment')
                        ->label('')
                        ->tooltip('Delete Equipment'),
                     
                    ], position: ActionsPosition::BeforeCells)
                
                
                  
                    
            
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
            // 'create' => Pages\CreateEquipment::route('/create'),
            // 'view' => Pages\ViewEquipment::route('/{record}'),
            // 'edit' => Pages\EditEquipment::route('/{record}/edit'),
            'qr-code' => Pages\ViewQrCode::route('/{record}/qr-code'),
        ];
    }
}
