<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesAndMaterialsResource\Pages;
use App\Models\SuppliesAndMaterials;
use App\Models\SuppliesCart; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
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
                Forms\Components\Section::make('')
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

        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
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
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('stocking_point')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make(array_merge($bulkActions, [
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
                                Forms\Components\TextInput::make('quantity_requested')
                                    ->label('Quantity Requested')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->hint("Available stock: {$availableStock}"),
                            ];
                        })
                        ->action(function (array $data, Collection $records) {
                            foreach ($records as $record) {
                                if ($data['quantity_requested'] > $record->quantity) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Error')
                                        ->body('Requested quantity exceeds available stock.')
                                        ->send();
                                    return;
                                }

                                // Create the SuppliesCart record
                                SuppliesCart::create([
                                    'user_id' => auth()->id(),
                                    'supplies_and_materials_id' => $record->id,
                                    'facility_id' => $record->facility_id,
                                    'available_quantity' => $record->quantity, // Copy available quantity
                                    'quantity_requested' => $data['quantity_requested'],
                                    'action_date' => now(), // Use current date as action date
                                ]);
                            }

                            Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Selected item/s have been added to your supplies cart.')
                                ->send();
                        }),
                ]))
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
