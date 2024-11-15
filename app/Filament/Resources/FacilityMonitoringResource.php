<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityMonitoringResource\Pages;
use App\Filament\Resources\FacilityMonitoringResource\RelationManagers;
use App\Models\FacilityMonitoring;
use Filament\Forms;
use App\Models\User;
use App\Models\Facility;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;

class FacilityMonitoringResource extends Resource
{
    protected static ?string $model = FacilityMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Monitoring Archive';
    protected static ?string $navigationLabel = 'Facility Monitoring';

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
        return $table
                ->query(FacilityMonitoring::query()
                ->with(['facility', 'user']) 
                )
            ->columns([
                Tables\Columns\TextColumn::make('facility.name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('monitored_date')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('F j, Y'))
                ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('monitored_date')
                ->label('Date Monitored')
                ->options(
                    FacilityMonitoring::query()
                        ->whereNotNull('monitored_date') // Filter out null values
                        ->pluck('monitored_date', 'monitored_date')
                        ->mapWithKeys(function ($date) {
                            // Format date using Carbon
                            return [$date => \Carbon\Carbon::parse($date)->format('F j, Y')];
                        })
                        ->toArray()
                ),
                SelectFilter::make('Facility')
                    ->relationship('facility','name'),
                SelectFilter::make('Monitored By')
                    ->relationship('user','name'),
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
            'index' => Pages\ListFacilityMonitorings::route('/'),
            'create' => Pages\CreateFacilityMonitoring::route('/create'),
            'edit' => Pages\EditFacilityMonitoring::route('/{record}/edit'),
        ];
    }
}
