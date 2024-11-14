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
        return $record->person_liable ?? ''; // Ensure the key is a string
    }

    protected function getTableQuery(): Builder
    {
        // Get start and end dates from page filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Query with optional date filters
        return Equipment::query()
            ->when($startDate, function (Builder $query) use ($startDate) {
                $query->whereDate('date_acquired', '>=', Carbon::parse($startDate));
            })
            ->when($endDate, function (Builder $query) use ($endDate) {
                $query->whereDate('date_acquired', '<=', Carbon::parse($endDate));
            })
            ->selectRaw('person_liable, COUNT(*) as equipment_count') // Update alias
            ->groupBy('person_liable');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('person_liable')
                ->label('Person Liable')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('equipment_count') // Use the alias for count
                ->label('Equipment Count')
                ->sortable(),
        ];
    }
}
