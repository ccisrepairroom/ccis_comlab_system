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
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;



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
                Forms\Components\Section::make('')
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
                                    ),
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
                                    //->options(array_combine(range(1, 1000), range(1, 1000)))
                                    ->label('Quantity'),
                                Forms\Components\TextInput::make('stocking_point')
                                    //->options(array_combine(range(1, 1000), range(1, 1000)))
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
                                        $set('stocking_point', null);  // Optionally reset the stocking_point
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
                                    /*Forms\Components\FileUpload::make('item_img')
                                    ->label('Item Image')
                                    ->preserveFilenames()
                                    ->multiple()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('supplies_and_materials'),*/
                                            
                                    
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
                            'requested_by' => $data['requested_by'], // This is where the value is passed
                            'supplies_and_materials_id' => $record->id,
                            'facility_id' => $record->facility_id,
                            'category_id' => $record->category_id,
                            'stock_unit_id' => $record->stock_unit_id,
                            'available_quantity' => $record->quantity, // Copy available quantity
                            'quantity_requested' => $data['quantity_requested'],
                            'remarks' => $data['remarks'],
                            'date_requested' => now(), // Use current date as action date
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
                Tables\Columns\TextColumn::make('item')
                    ->label('Item')                
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('category.description')  
                    ->label('Category')  
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    /*->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->quantity} {$stockUnitDescription}";
                    })*/
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stocking_point')
                    ->searchable()
                    ->sortable()
                    /*->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->stocking_point} {$stockUnitDescription}";
                    })*/
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
            ->filters([
                    SelectFilter::make('item')
                    ->label('Item')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('item') // Filter out null values
                            ->pluck('item', 'item')
                            ->toArray()
                    ),
                    SelectFilter::make('Category')
                    ->relationship('category','description'),
                    
                    //->searchable ()
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
                    /*SelectFilter::make('created_at')
                    ->label('Created At')
                    ->options(
                        SuppliesAndMaterials::query()
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
            ->actions([
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('AddStock')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-pencil')
                    ->modalHeading('Add Stock')
                    ->modalDescription('Enter the quantity to adjust stocks.')
                    ->form(function (Forms\Form $form, $record) {
                        return $form->schema([
                            Forms\Components\Select::make('monitored_by')
                                ->label('Monitored By')
                                ->options(User::all()->pluck('name', 'id'))
                                ->default(auth()->user()->id)
                                ->disabled()
                                ->required(),
                            Forms\Components\Select::make('supplies_and_materials_id')
                                ->label('Item')
                                ->options(SuppliesAndMaterials::all()->pluck('item', 'id'))
                                ->default(SuppliesAndMaterials::first()->id) 
                                ->disabled(),
                            
                            Forms\Components\DatePicker::make('monitored_date')
                                ->label('Monitoring Date')
                                ->required()
                                ->default(now())
                                ->reactive()
                                ->afterStateUpdated(function ($set, $state) {
                                    // Optionally, you can validate or format it here
                                    $set('date_acquired', \Carbon\Carbon::parse($state)->format('M-d-y'));
                                }),
                            Forms\Components\Select::make('current_quantity')
                                ->label('Current Quantity')
                                ->default(function ($get) use ($record) {
                                    $item = SuppliesAndMaterials::find($record->supplies_and_materials_id);
                                    return $item ? $item->quantity : 0; // Return 0 if no item found
                                })
                                ->hidden()
                                ->disabled(),
                            
                            
                            Forms\Components\TextInput::make('quantity_to_add')
                                ->label('Quantity to Add')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue($record->quantity + 100) // Adjust maxValue for adding
                                ->hint("Current Stock: {$record->quantity}")
                                ->required(),
                            Forms\Components\TextInput::make('supplier')
                                ->label('Supplier')
                                ->default($record->supplier)
                        ]);
                    })
                    ->action(function (array $data, $record) {
                        $monitoredDate = $data['monitored_date'] ?? now()->format('M-d-y');
                        // Use quantity_to_add to adjust stock
                        $newStock = $record->quantity + $data['quantity_to_add'];

                        // Check if quantity is sufficient for deduction, if applicable
                        if ($data['quantity_to_add'] < 0) {
                            $newStock = $record->quantity + $data['quantity_to_add']; // deducting
                            if ($newStock < 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('Error')
                                    ->body('Insufficient stock. Cannot deduct more than available stock.')
                                    ->send();
                                return;
                            }
                        }

                        \App\Models\StockMonitoring::create([
                            'supplies_and_materials_id' => $record->supplies_and_materials_id ?? $record->id,
                            'facility_id' => $record->facility_id,
                            'monitored_by' => auth()->user()->id,
                            'current_quantity' => $record->quantity,
                            'quantity_to_add' => $data['quantity_to_add'],
                            'new_quantity' => $newStock,
                            'supplier' => $data['supplier'],
                            'monitored_date' => $data['monitored_date'],
                        ]);

                        $record->update(['quantity' => $newStock]);

                        Notification::make()
                            ->success()
                            ->title('Stock Adjusted')
                            ->body('Stock quantity for this item has been successfully adjusted.')
                            ->send();
                    })
                    ->hidden(fn () => $isFaculty),
                   
                
            ])
            
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
