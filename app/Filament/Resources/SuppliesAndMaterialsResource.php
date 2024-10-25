<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesAndMaterialsResource\Pages;
use App\Filament\Resources\SuppliesAndMaterialsResource;
use App\Models\SuppliesAndMaterials;
use App\Models\StockUnit;
use App\Models\Facility;
use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification; // Import Notification
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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
                Forms\Components\Section::make('Equipment Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->placeholder('Name of an item')
                                    ->label('Item')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('quantity')
                                    ->required()
                                    ->options(array_combine(range(1, 1000), range(1, 1000)))
                                    ->label('Quantity'),
                                Forms\Components\Select::make('stocking_point')
                                    ->options(array_combine(range(1, 1000), range(1, 1000)))
                                    ->label('Stocking Point'),
                                Forms\Components\Select::make('stock_unit_id')
                                    ->label('Stock Unit')
                                    ->relationship('stockUnit', 'description')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('description')
                                            ->label('Create Stock Unit')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Select::make('facility_id')
                                    ->label('Location')
                                    ->relationship('facility', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Create Location')
                                            ->placeholder('Enter the facility where an item is located ')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
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
            Tables\Actions\BulkAction::make('add_to_supplies_cart')
                ->label('Add to Supplies Cart')
                ->icon('heroicon-o-shopping-cart')
                ->action(function (Collection $records) {
                    $notAdded = [];
                    $successfullyAdded = false;

                    foreach ($records as $record) {
                        if ($record->quantity <= 0) {
                            // If quantity is 0, add to not added list
                            $notAdded[] = $record->item;
                            continue; // Skip adding this item
                        }

                        // Logic to add the item to the cart (replace this with actual cart logic)
                        $added = addToCart($record); // Placeholder for your actual logic

                        if ($added) {
                            $successfullyAdded = true; // At least one item was successfully added
                        } else {
                            $notAdded[] = $record->item; // Keep track of items that couldn't be added
                        }
                    }

                    // Notification for out of stock items
                    if (!empty($notAdded)) {
                        Notification::make()
                            ->title('Items Not Added')
                            ->body('The following items cannot be added to the cart because they are out of stock: ' . implode(', ', $notAdded))
                            ->danger()
                            ->send();
                    }

                    // Send success notification only if at least one item was added
                    if ($successfullyAdded) {
                        Notification::make()
                            ->success()
                            ->title('Success')
                            ->body('Selected items have been successfully added to your cart.')
                            ->send();
                    }
                })
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-check')
                ->modalHeading('Add to Supplies Cart')
                ->modalDescription('Confirm to add selected supplies and materials to your supplies cart'),
        ];

        // Conditionally add ExportBulkAction
        if (!$isPanelUser) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item')
                    ->label('Item')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $stockUnitDescription = $record->stockUnit ? $record->stockUnit->description : "";
                        return "{$record->stocking_point} {$stockUnitDescription}";
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stocking_point')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Location')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                
            ])
            ->filters([
                // Define your filters here
            ])
            ->recordUrl(function ($record) {
                //return Pages\ViewSupplies::getUrl([$record->id]);
            })
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define your relations here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliesAndMaterials::route('/'),
            'create' => Pages\CreateSuppliesAndMaterials::route('/create'),
            'edit' => Pages\EditSuppliesAndMaterials::route('/{record}/edit'),
            //'view' => Pages\ViewSupplies::route('/{record}'),
        ];
    }
}
