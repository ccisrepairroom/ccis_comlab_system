<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesCartResource\Pages;
use App\Models\SuppliesCart;
use App\Models\SuppliesAndMaterials; 
use App\Models\Category;
use App\Models\StockUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;


class SuppliesCartResource extends Resource
{
    protected static ?string $model = SuppliesCart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Supplies And Materials';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('requested_by')
                    ->label('Requested_by')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('supplies_and_materials_id')
                    ->label('Supplies and Materials ID')
                    ->required()
                    ->maxLength(255)
                    ->hidden(), // Optionally hide if you handle this automatically
                Forms\Components\TextInput::make('available_quantity')
                    ->label('Available Quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity_requested')
                    ->label('Quantity Requested')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('action_date')
                    ->label('Action Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(SuppliesCart::with('stockUnit'))
            ->columns([
                Tables\Columns\TextColumn::make('requested_by')
                    ->label('Requested By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplies_and_materials.item')
                    ->label('Item')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_requested')
                    ->label('Quantity Requested')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->quantity_requested} {$stockUnitDescription}";
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Available Quantity')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->available_quantity} {$stockUnitDescription}";
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('action_date')
                ->label('Date Requested')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn ($state) => 
                    \Carbon\Carbon::parse($state ?: now()) 
                        ->timezone('Asia/Manila')
                        ->format('F j, Y') 
                )
                ->sortable(),
            ])
            ->filters([
                    /*SelectFilter::make('item')
                    ->label('Item')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('item') // Filter out null values
                            ->pluck('item', 'item')
                            ->toArray()
                    ),*/
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliesCarts::route('/'),
            'create' => Pages\CreateSuppliesCart::route('/create'),
            'edit' => Pages\EditSuppliesCart::route('/{record}/edit'),
        ];
    }
}
