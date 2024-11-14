<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Builder;

class FacilityPerFloorLevel extends BaseWidget
{
    protected static ?string $heading = 'CCIS Facilities';
    protected int | string | array $columnSpan = 3;

    protected function getTableQuery(): Builder
    {
        // Ensure that the query returns a valid Builder instance
        return Facility::query(); // Optional: Add where conditions if needed
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Facility Name')
                ->sortable()
                ->searchable(),
            
            Tables\Columns\TextColumn::make('floor_level')
                ->label('Floor Level')
                ->sortable()
                ->searchable(),
            
            Tables\Columns\TextColumn::make('connection_type')
                ->label('Connection Type')
                ->sortable()
                ->searchable(),
            
            Tables\Columns\TextColumn::make('facility_type')
                ->label('Facility Type')
                ->sortable()
                ->searchable(),
        ];
    }

    // Optional: Override initializeTable if needed
    protected function initializeTable()
    {
        // Custom table initialization logic (if needed)
        parent::initializeTable();
    }
}
