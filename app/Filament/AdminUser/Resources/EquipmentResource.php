<?php

namespace App\Filament\AdminUser\Resources;

use App\Filament\AdminUser\Resources\EquipmentResource\Pages;
use App\Filament\AdminUser\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Equipment Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'description')
                                    ->required(),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->default(auth()->id()),
                                Forms\Components\Select::make('facility_id')
                                    ->relationship('facility', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('unit_id')
                                    ->label('Unit ID')
                                    ->disabled() // Make unit_id read-only
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn () => Equipment::generateUniqueUnitID()),
                                Forms\Components\TextInput::make('brand_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('item_number')
                                    ->label('Item Number')
                                    ->disabled() // Make item_number read-only
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn () => Equipment::generateUniqueItemNumber()),
                                Forms\Components\TextInput::make('property_number')
                                    ->label('Property Number')
                                    ->disabled()
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn () => Equipment::generateUniquePropertyNumber()),
                                Forms\Components\TextInput::make('control_number')
                                    ->label('Control Number')
                                    ->disabled()
                                    ->required()
                                    ->maxLength(255)
                                    ->default(fn () => Equipment::generateUniqueControlNumber()),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'Available' => 'Available',
                                        'Out of Stock' => 'Out of Stock',
                                        'For Replacement' => 'For Replacement',
                                        'Borrowed' => 'Borrowed',
                                        'Returned' => 'Returned',
                                    ]),
                                Forms\Components\TextInput::make('date_acquired')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('supplier')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('specification')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
                Section::make('Facility Image')
                    ->schema([
                        Forms\Components\TextInput::make('facility_img')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('facility_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('property_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('control_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_acquired')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
