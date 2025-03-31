<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Equipment;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PersonLiable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Equipment Count by Person Liable';
    protected int | string | array $columnSpan = 3;
    protected static bool $isLazy = false;

    public function getTableQuery(): Builder
    {
        $unknownUserId = \App\Models\User::where('name', 'Unknown')->value('id') ?? 0;

        return Equipment::query()
            ->selectRaw("COALESCE(user_id, ?) as user_id, COUNT(*) as equipment_count", [$unknownUserId])
            ->groupBy('user_id')
            ->orderByDesc('equipment_count');
    }

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user_id')
                ->label('Person Liable')
                ->formatStateUsing(fn ($state) => \App\Models\User::find($state)?->name ?? 'Unknown')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('equipment_count')
                ->label('Equipment Count')
                ->sortable(),
        ];
    }

    public function getTableRecordKey(Model $record): string
    {
        return (string) $record->user_id; // Ensure it's a string
    }
}
