<?php

namespace App\Filament\Widgets;

namespace App\Filament\Resources\AdminResource\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Equipment;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class PersonLiableTableWidget  extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Equipment Count Person Liable';
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

        // Return a query builder for counting equipment grouped by person_liable, filtered by date_acquired
        return Equipment::query()
            ->when($startDate, function (Builder $query) use ($startDate) {
                $query->whereDate('date_acquired', '>=', Carbon::parse($startDate));
            })
            ->when($endDate, function (Builder $query) use ($endDate) {
                $query->whereDate('date_acquired', '<=', Carbon::parse($endDate));
            })
            ->selectRaw('person_liable, COUNT(*) as count')
            ->groupBy('person_liable');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('person_liable')
                ->label('Person Liable')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('count')
                ->label('Equipment Count')
                ->sortable(),
        ];
    }
}
