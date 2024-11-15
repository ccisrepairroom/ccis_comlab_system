<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMonitoringResource\Pages;
use App\Models\StockMonitoring;
use App\Models\SuppliesAndMaterials;
use App\Models\Facility;
use App\Models\User;
use App\Models\StockUnit;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockMonitoringResource extends Resource
{
    protected static ?string $model = StockMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Supplies And Materials';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                // Form schema here
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(StockMonitoring::query()->with('suppliesAndMaterials')) // This pulls all records from the stock_monitorings table
            ->columns([
                //Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('monitored_date')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('F j, Y'))
                ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Monitored By')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('suppliesAndMaterials.item')
                ->label('Item')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                /*->options(function ($record) {
                    return SuppliesAndMaterials::pluck('item', 'id');  
                })*/
                ->sortable(),
                Tables\Columns\TextColumn::make('facility.name')
                ->label('Facility')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
               
                Tables\Columns\TextColumn::make('current_quantity')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(function ($record) {
                    $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                    return "{$record->current_quantity} {$stockUnitDescription}";
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('quantity_to_add')
                ->label('Quantity Added')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('new_quantity')
                ->label('New Quantity')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('supplier')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                
            ])
            ->filters([
                // Optional filters, if needed
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
            // Relations, if any
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMonitorings::route('/'),
            'create' => Pages\CreateStockMonitoring::route('/create'),
            'edit' => Pages\EditStockMonitoring::route('/{record}/edit'),
        ];
    }
}
