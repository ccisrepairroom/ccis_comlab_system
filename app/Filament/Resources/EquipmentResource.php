<?php

namespace App\Filament\Resources;

//use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use App\Models\Facility;
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

                               
                                Forms\Components\TextInput::make('unit_no')
                                    ->placeholder('Set number pasted on the Comlab table.')
                                    ->label('Unit Number')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('brand_name')
                                    ->placeholder('Brand Name of Equipment')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('description')
                                    ->placeholder('specifications, e.g., dimensions, weight, power')
                                    ->maxLength(255),
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
                                    ->placeholder('Refer to the equipment sticker.')
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
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('control_no')
                                    ->label('Control Number')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('serial_no')
                                    ->label('Serial Number')
                                    ->placeholder('Refer to the Equipment sticker.')
                                    ->maxLength(255),
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
        $isPanelUser = $user && $user->hasRole('panel_user');
    
        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            //Tables\Actions\EditBulkAction::make(),
        ];
    
        // Conditionally add ExportBulkAction
        if (!$isPanelUser) {
            $bulkActions[] = ExportBulkAction::make();
        }
    
       
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit No.')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('brand_name')
                    ->label('Brand Name')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    ->color(fn(string $state): string => match ($state) {
                        'Working' => 'success',
                        'For Repair' => 'warning',
                        'For Replacement' => 'primary',
                        'Lost' => 'danger',
                        'For Disposal' => 'primary',
                        'Disposed' => 'danger',
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
                    ->label('Item No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('property_no')
                    ->searchable()
                    ->label('Property No.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('control_no')
                    ->searchable()
                    ->label('Control No.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('serial_no')
                    ->searchable()
                    ->label('Serial No.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    SelectFilter::make('Category')
                    ->relationship('category','description'),
                    
                    //->searchable ()
                    SelectFilter::make('Facility')
                    ->relationship('facility','name'),
                    SelectFilter::make('po_number')
                    ->label('PO Number')
                    ->options(
                        Equipment::query()
                            ->whereNotNull('po_number') // Filter out null values
                            ->pluck('po_number', 'po_number')
                            ->toArray()
                    ),
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
                     
                    
                ])
                /*->recordUrl(function ($record) {
                    return Pages\ViewEquipment::getUrl([$record->id]);
                })*/
                ->actions([
                   
                    Tables\Actions\ActionGroup::make([
                       // ListPreviewAction::make(),
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\ViewAction::make('view_monitoring')
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
                            }),
                       
                        

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
