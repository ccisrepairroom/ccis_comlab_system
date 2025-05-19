<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesAndMaterialsResource\Pages;
use App\Models\SuppliesAndMaterials;
use App\Models\SuppliesCart; 
use App\Models\Category; 
use App\Models\StockUnit; 
use App\Models\StockMonitoring; 
use App\Models\User; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;





class SuppliesAndMaterialsResource extends Resource
{
    protected static ?string $model = SuppliesAndMaterials::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Supplies And Materials';
    protected static ?int $navigationSort = 3;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form

                ->schema([
                    Section::make('Item Image')
                    ->schema([
                        Forms\Components\FileUpload::make('main_image')
                        ->imageEditor()
                        ->deletable()
                        ->preserveFilenames(),
     
                        ])
                        ->columnSpan(1)
                        ->columns(1)
                        ->collapsible(),

                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->placeholder('Name of an item')
                                    ->label('Item')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        table: 'supplies_and_materials', 
                                        column: 'item', 
                                        ignoreRecord: true
                                    )
                                    ->validationMessages([
                                        'unique' => 'This item name already exists.',
                                    ]),
                                Forms\Components\TextInput::make('description')
                                    ->placeholder('Specifications, e.g., dimensions, weight, power'),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'description')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('description')
                                        ->label('Create Category')
                                        ->placeholder('E.g., Monitor, System Unit, AVR/UPS, etc.')
                                        ->required()
                                        ->maxLength(255)
                                       
                                    ]),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->label('Quantity'),
                                Forms\Components\TextInput::make('stocking_point')
                                    ->label('Stocking Point')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->reactive()
                                ->afterStateUpdated(function (callable $set, $state, $get) {
                                    // Get the values of quantity and stocking_point
                                    $quantity = $get('quantity');
                                    
                                    // Check if stocking_point exceeds quantity
                                    if ($state > $quantity) {
                                        // Set error message if stocking_point exceeds quantity
                                        $set('stocking_point', null);  
                                        Notification::make()
                                            ->danger()
                                            ->title('Try Again')
                                            ->body('Stocking Point cannot exceed Quantity.')
                                            ->send();
                                    }
                                }),
                            
                                Forms\Components\Select::make('stock_unit_id')
                                    ->label('Stock Unit')
                                    ->required()
                                    ->relationship('stockUnit', 'description')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('description')
                                            ->label('Create Stock Unit')
                                            ->placeholder('E.g., Tray, Carton, Box, etc.')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Select::make('facility_id')
                                    ->label('Facility')
                                    ->required()
                                    ->relationship('facility', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Create Location')
                                            ->placeholder('Enter the facility where an item is located ')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                
                                Forms\Components\TextInput::make('date_acquired')
                                    ->label('Date Acquired')
                                    ->placeholder('mm-dd-yy. E.g., 01-28-24')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('supplier')
                                    ->label('Supplier')
                                    ->placeholder('Refer to the item sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('remarks')
                                    ->label('Remarks'),
                                     
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
       
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),

            Tables\Actions\BulkAction::make('bulk_update')
            ->label('Bulk Update')
            ->icon('entypo-cycle')
            ->color('warning')
            ->requiresConfirmation()
            ->modalIcon('entypo-cycle')
            ->modalHeading('Bulk Update Item Details')
            ->modalDescription('Select the fields you want to update.')
            ->form(function (Forms\Form $form) {
                return $form->schema([
                    // Step 1: Select columns to update
                    Forms\Components\CheckboxList::make('fields_to_update')
                        ->options([
                            'main_image' => 'Item Image',
                            'description' => 'Description',
                            'category_id' => 'Category',
                            'facility_id' => 'Facility',
                            'quantity' => 'Quantity',
                            'stocking_point' => 'Stocking Point',
                            'stock_unit_id' => 'Stock Unit',
                            'date_acquired' => 'Date Acquired',
                            'supplier' => 'Supplier',
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
                        
                    Forms\Components\TextInput::make('description')
                        ->placeholder('Specifications, e.g., dimensions, weight, power')
                        ->visible(fn ($get) => in_array('description', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('description', $get('fields_to_update') ?? [])),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'description')
                        ->visible(fn ($get) => in_array('category_id', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('category_id', $get('fields_to_update') ?? [])),

                    Forms\Components\Select::make('facility_id')
                        ->label('Facility')
                        ->required()
                        ->relationship('facility', 'name')
                        ->visible(fn ($get) => in_array('facility_id', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('facility_id', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->visible(fn ($get) => in_array('quantity', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('quantity', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('stocking_point')
                        ->label('Stocking Point')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state, $get) {
                            // Get the values of quantity and stocking_point
                            $quantity = $get('quantity');
                            
                            // Check if stocking_point exceeds quantity
                            if ($state > $quantity) {
                                // Set error message if stocking_point exceeds quantity
                                $set('stocking_point', null);  
                                Notification::make()
                                    ->danger()
                                    ->title('Try Again')
                                    ->body('Stocking Point cannot exceed Quantity.')
                                    ->send();
                                    }
                                 })
                        ->visible(fn ($get) => in_array('stocking_point', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('stocking_point', $get('fields_to_update') ?? [])),

                    Forms\Components\Select::make('stock_unit_id')
                        ->label('Stock Unit')
                        ->required()
                        ->relationship('stockUnit', 'description')
                        ->visible(fn ($get) => in_array('stock_unit_id', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('stock_unit_id', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('date_acquired')
                        ->label('Date Acquired')
                        ->placeholder('mm-dd-yy. E.g., 01-28-24')
                        ->maxLength(255)
                        ->visible(fn ($get) => in_array('date_acquired', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('date_acquired', $get('fields_to_update') ?? [])),
                    
                    Forms\Components\TextInput::make('supplier')
                        ->label('Supplier')
                        ->placeholder('Refer to the item sticker.')
                        ->maxLength(255)
                        ->visible(fn ($get) => in_array('supplier', $get('fields_to_update') ?? [])) 
                        ->required(fn ($get) => in_array('supplier', $get('fields_to_update') ?? [])),

                    Forms\Components\TextInput::make('remarks')
                        ->label('Remarks')
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
                        if (in_array('item', $data['fields_to_update'])) {
                            $updateData['item'] = $data['item'];
                        }
                        if (in_array('description', $data['fields_to_update'])) {
                            $updateData['description'] = $data['description'];
                        }
                        if (in_array('facility_id', $data['fields_to_update'])) {
                            $updateData['facility_id'] = $data['facility_id'];
                        }
                        if (in_array('quantity', $data['fields_to_update'])) {
                            $updateData['quantity'] = $data['quantity'];
                        }
                        if (in_array('stocking_point', $data['fields_to_update'])) {
                            $updateData['stocking_point'] = $data['stocking_point'];
                        }
                        if (in_array('stock_unit_id', $data['fields_to_update'])) {
                            $updateData['stock_unit_id'] = $data['stock_unit_id'];
                        }
                        if (in_array('date_aquired', $data['fields_to_update'])) {
                            $updateData['date_aquired'] = $data['date_aquired'];
                        }
                        if (in_array('supplier', $data['fields_to_update'])) {
                            $updateData['supplier'] = $data['supplier'];
                        }
                        if (in_array('remarks', $data['fields_to_update'])) {
                            $updateData['remarks'] = $data['remarks'];
                        }
                        $record->update($updateData);
                    }
            
                    \Filament\Notifications\Notification::make()
                        ->title('Facilities updated successfully!')
                        ->success()
                        ->send();
                }),

            Tables\Actions\BulkAction::make('bulk_update_stock')
                ->modalHeading('Reorder Stock')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-pencil')
                ->modalHeading('Reorder Stock')
                ->modalDescription('Enter the quantity and supplier to adjust stocks for selected items.')
                ->form(function (Forms\Form $form) {
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
                ->default(now()),

            Forms\Components\TextInput::make('quantity_to_add')
                ->label('Quantity to Add (Applied to all selected items)')
                ->required()
                ->numeric()
                ->minValue(1),

            Forms\Components\TextInput::make('supplier')
                ->label('Supplier')
                ->required()
                ]);
            })
            ->action(function (array $data, $records) {
                foreach ($records as $record) {
                    $newStock = $record->quantity + $data['quantity_to_add'];

                    // Ensure stock does not go negative
                    if ($data['quantity_to_add'] < 0 && $newStock < 0) {
                        Notification::make()
                            ->danger()
                            ->title('Error')
                            ->body("Insufficient stock for item {$record->id}. Cannot deduct more than available stock.")
                            ->send();
                        continue; 
                    }

                    // Log stock change in StockMonitoring table
                    \App\Models\StockMonitoring::create([
                        'supplies_and_materials_id' => $record->id,
                        'facility_id' => $record->facility_id,
                        'monitored_by' => auth()->user()->id,
                        'current_quantity' => $record->quantity,
                        'quantity_to_add' => $data['quantity_to_add'],
                        'new_quantity' => $newStock,
                        'supplier' => $data['supplier'],  
                        'monitored_date' => $data['monitored_date'],
                    ]);

                    // Update the item's stock and supplier
                    $record->update([
                        'quantity' => $newStock,
                        'supplier' => $data['supplier'],  
                    ]);
                }

                Notification::make()
                    ->success()
                    ->title('Stock Updated')
                    ->body('Stock quantities and suppliers have been successfully updated for the selected items.')
                    ->send();
            }),



            Tables\Actions\BulkAction::make('add_to_supplies_cart')
                ->label('Add to Supplies Cart')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Add to Supplies Cart')
                ->modalDescription('Confirm to add selected item/s to your supplies cart.')
                ->form(function (Collection $records) {
                    $availableStock = $records->sum('quantity');
                    return [
                        Forms\Components\TextInput::make('requested_by')
                            ->required()
                            ->label('Requested By:'),
                        Forms\Components\TextInput::make('quantity_requested')
                            ->label('Quantity Requested')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->hint("Available stock: {$availableStock}"),
                        Forms\Components\TextInput::make('remarks')
                            ->label('Remarks'),
                    ];

                    
                })
                
                
                ->action(function (array $data, Collection $records) {
                    foreach ($records as $record) {
                        // Check if requested quantity is available
                        if ($data['quantity_requested'] > $record->quantity) {
                            Notification::make()
                                ->danger()
                                ->title('Try Again')
                                ->body('Requested quantity exceeds available stock.')
                                ->send();
                            return;
                        }
                
                        // Create the SuppliesCart record with the requested_by field from $data
                        SuppliesCart::create([
                            'user_id' => auth()->id(),
                            'requested_by' => $data['requested_by'], 
                            'supplies_and_materials_id' => $record->id,
                            'facility_id' => $record->facility_id,
                            'category_id' => $record->category_id,
                            'stock_unit_id' => $record->stock_unit_id,
                            'available_quantity' => $record->quantity, 
                            'quantity_requested' => $data['quantity_requested'],
                            'remarks' => $data['remarks'],
                            'date_requested' => now(), 
                        ]);
                
                        // Deduct the requested quantity from available stock
                        $record->quantity -= $data['quantity_requested'];
                        $record->save(); // Save the updated stock quantity
                    }
                
                    Notification::make()
                        ->success()
                        ->title('Success')
                        ->body('Selected item/s have been added to your supplies cart.')
                        ->send();
                }),
        ];

        if (!$isFaculty) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
            ->description('Supplies refer to consumable items. To request, select an item. An "Actions" button will appear. Click it and choose "Add to Supplies Cart".
            For more information, go to the dashboard to download the user manual.')
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                ->label('Main Image')
                ->stacked(),
                Tables\Columns\TextColumn::make('item')
                    ->label('Item')                
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
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
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state))),
                Tables\Columns\TextColumn::make('category.description')  
                    ->label('Category')  
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stocking_point')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stockunit.description')
                    ->label('Stock Unit')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Facility')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_acquired')
                    ->label('Date Acquired')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->recordUrl(fn ($record) => route('supplies-and-materials-monitoring', ['supply' => $record->id]))
            ->openRecordUrlInNewTab()

            ->filters([
                    SelectFilter::make('item')
                    ->label('Item')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('item') 
                            ->pluck('item', 'item')
                            ->toArray()
                    ),
                    SelectFilter::make('Category')
                    ->relationship('category','description'),
                    SelectFilter::make('Facility')
                    ->relationship('facility','name'),
                    SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('supplier') // Filter out null values
                            ->pluck('supplier', 'supplier')
                            ->toArray()
                    ),
                    SelectFilter::make('date_acquired')
                    ->label('Date Acquired')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('date_acquired') // Filter out null values
                            ->pluck('date_acquired', 'date_acquired')
                            ->toArray()
                    ),
                  
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->tooltip('View Item')
                    ->url(fn (SuppliesAndMaterials $record) => route('supplies-and-materials-monitoring', ['supply' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Edit Item'),
                // Tables\Actions\Action::make('AddStock')
                //     ->icon('heroicon-o-pencil')
                //     ->color('warning')
                //     ->requiresConfirmation()
                //     ->modalIcon('heroicon-o-pencil')
                //     ->modalHeading('Add Stock')
                //     ->modalDescription('Enter the quantity to adjust stocks.')
                //     ->form(function (Forms\Form $form, $record) {
                //         return $form->schema([
                //             Forms\Components\Select::make('monitored_by')
                //                 ->label('Monitored By')
                //                 ->options(User::all()->pluck('name', 'id'))
                //                 ->default(auth()->user()->id)
                //                 ->disabled()
                //                 ->required(),
                //             Forms\Components\Select::make('supplies_and_materials_id')
                //                 ->label('Item')
                //                 ->options(SuppliesAndMaterials::all()->pluck('item', 'id'))
                //                 ->default(SuppliesAndMaterials::first()->id) 
                //                 ->disabled(),
                            
                //             Forms\Components\DatePicker::make('monitored_date')
                //                 ->label('Monitoring Date')
                //                 ->required()
                //                 ->default(now())
                //                 ->reactive()
                //                 ->afterStateUpdated(function ($set, $state) {
                //                     // Optionally, you can validate or format it here
                //                     $set('date_acquired', \Carbon\Carbon::parse($state)->format('M-d-y'));
                //                 }),
                //             Forms\Components\Select::make('current_quantity')
                //                 ->label('Current Quantity')
                //                 ->default(function ($get) use ($record) {
                //                     $item = SuppliesAndMaterials::find($record->supplies_and_materials_id);
                //                     return $item ? $item->quantity : 0; // Return 0 if no item found
                //                 })
                //                 ->hidden()
                //                 ->disabled(),
                            
                            
                //             Forms\Components\TextInput::make('quantity_to_add')
                //                 ->label('Quantity to Add')
                //                 ->required()
                //                 ->numeric()
                //                 ->minValue(1)
                //                 ->maxValue($record->quantity + 100) // Adjust maxValue for adding
                //                 ->hint("Current Stock: {$record->quantity}")
                //                 ->required(),
                //             Forms\Components\TextInput::make('supplier')
                //                 ->label('Supplier')
                //                 ->default($record->supplier)
                //         ]);
                //     })
                //     ->action(function (array $data, $record) {
                //         $monitoredDate = $data['monitored_date'] ?? now()->format('M-d-y');
                //         // Use quantity_to_add to adjust stock
                //         $newStock = $record->quantity + $data['quantity_to_add'];

                //         // Check if quantity is sufficient for deduction, if applicable
                //         if ($data['quantity_to_add'] < 0) {
                //             $newStock = $record->quantity + $data['quantity_to_add']; // deducting
                //             if ($newStock < 0) {
                //                 Notification::make()
                //                     ->danger()
                //                     ->title('Error')
                //                     ->body('Insufficient stock. Cannot deduct more than available stock.')
                //                     ->send();
                //                 return;
                //             }
                //         }

                //         \App\Models\StockMonitoring::create([
                //             'supplies_and_materials_id' => $record->supplies_and_materials_id ?? $record->id,
                //             'facility_id' => $record->facility_id,
                //             'monitored_by' => auth()->user()->id,
                //             'current_quantity' => $record->quantity,
                //             'quantity_to_add' => $data['quantity_to_add'],
                //             'new_quantity' => $newStock,
                //             'supplier' => $data['supplier'],
                //             'monitored_date' => $data['monitored_date'],
                //         ]);

                //         $record->update(['quantity' => $newStock]);

                //         Notification::make()
                //             ->success()
                //             ->title('Stock Adjusted')
                //             ->body('Stock quantity for this item has been successfully adjusted.')
                //             ->send();

                        


                //     }),
                    Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Delete Item')
                    ->hidden(fn () => $isFaculty),
                   
                
            ], position: ActionsPosition::BeforeCells)
            
            ->bulkActions([
               

                Tables\Actions\BulkActionGroup::make(array_merge($bulkActions, [

                   
                ]))
                ->label('Actions') 
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliesAndMaterials::route('/'),
            'create' => Pages\CreateSuppliesAndMaterials::route('/create'),
            'edit' => Pages\EditSuppliesAndMaterials::route('/{record}/edit'),
        ];
    }
}
