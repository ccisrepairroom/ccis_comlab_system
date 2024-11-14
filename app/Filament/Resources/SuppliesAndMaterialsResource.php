<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesAndMaterialsResource\Pages;
use App\Models\SuppliesAndMaterials;
use App\Models\SuppliesCart; 
use App\Models\Category; 
use App\Models\StockUnit; 
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
                                    ->label('Location')
                                    ->required()
                                    ->relationship('facility', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Create Location')
                                            ->placeholder('Enter the facility where an item is located ')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
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
        $isPanelUser = $user && $user->hasRole('panel_user');

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

        if (!$isPanelUser) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
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
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->quantity} {$stockUnitDescription}";
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stocking_point')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->stocking_point} {$stockUnitDescription}";
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
