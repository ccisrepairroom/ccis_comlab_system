<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliesCartResource\Pages;
use App\Models\SuppliesCart;
use App\Models\SuppliesAndMaterials; // Include the SuppliesAndMaterials model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->maxLength(255)
                    ->hidden(), // Optionally hide if you handle this automatically
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
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplies_and_materials_id')
                    ->label('Supplies and Materials ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Available Quantity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_requested')
                    ->label('Quantity Requested')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action_date')
                    ->label('Action Date')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // Add any necessary filters
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
