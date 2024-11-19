<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Equipment;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class PersonLiable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Equipment Count by Person Liable';
    protected int | string | array $columnSpan = 3;

    // Ensure each record has a unique key
    public function getTableRecordKey($record): string
    {
        // Use person_liable as a unique key for this example, or you can use any other field that is unique per record
        return $record->person_liable ?? ''; // Ensure that the key is a string
    }

    protected function getTableQuery(): Builder
    {
        
        // Get start and end dates from the page filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        return Equipment::query()
        ->when($startDate, function (Builder $query) use ($startDate) {
            // Apply the start date filter
            $query->whereDate('date_acquired', '>=', Carbon::parse($startDate));
        })
        ->when($endDate, function (Builder $query) use ($endDate) {
            // Apply the end date filter
            $query->whereDate('date_acquired', '<=', Carbon::parse($endDate));
        })
        ->selectRaw('person_liable, COUNT(*) as count')  // Count the equipment per person_liable
        ->groupBy('person_liable');  // Group by person_liable
    }
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('person_liable')
            ->label('Person Liable')
            ->sortable()
            ->searchable(),

        // Display the count of equipment assigned to each person
        Tables\Columns\TextColumn::make('count')
            ->label('Equipment Count')
            ->sortable(),
    ];
    }
}