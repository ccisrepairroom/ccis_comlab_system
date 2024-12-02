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
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class StockMonitoringResource extends Resource
{
    protected static ?string $model = StockMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Monitoring History';
    protected static ?string $navigationLabel = 'Stock Monitoring';
    protected static ?int $navigationSort = 7;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                // Form schema here
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        
        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make()
        ];
         // Conditionally add ExportBulkAction
         if (!$isFaculty) {
            $bulkActions[] = ExportBulkAction::make();
        }
        

        return $table
        ->query(StockMonitoring::query()->with('suppliesAndMaterials')) // This pulls all records from the stock_monitorings table
        ->description('This page contains the history of restock monitoring. For more information, go to the dashboard to download the user manual.')    
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
                Tables\Columns\TextColumn::make('suppliesAndMaterials.stockunit.description')
                ->label('Stock Unit')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                /*->options(function ($record) {
                    return SuppliesAndMaterials::pluck('item', 'id');  
                })*/
                ->sortable(),
                Tables\Columns\TextColumn::make('supplier')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                
            ])
            ->filters([
                SelectFilter::make('monitored_date')
                    ->label('Date Monitored')
                    ->options(
                        StockMonitoring::query()
                            ->whereNotNull('monitored_date') // Filter out null values
                            ->pluck('monitored_date', 'monitored_date')
                            ->mapWithKeys(function ($date) {
                                // Format date using Carbon
                                return [$date => \Carbon\Carbon::parse($date)->format('F j, Y')];
                            })
                            ->toArray()
                    ),
                SelectFilter::make('item')
                    ->label('Item')
                    ->options(
                        SuppliesAndMaterials::query()
                            ->whereNotNull('item') // Filter out null values
                            ->pluck('item', 'item')
                            ->toArray()
                    ),
                    SelectFilter::make('Monitored By')
                    ->relationship('user','name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
            ])
            ->bulkActions([
               Tables\Actions\BulkActionGroup::make($bulkActions)
                ->label('Actions')
                
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
