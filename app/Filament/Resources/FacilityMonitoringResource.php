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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class FacilityMonitoringResource extends Resource
{
    protected static ?string $model = FacilityMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Monitoring History';
    protected static ?string $navigationLabel = 'Facility Monitoring';
    protected static ?int $navigationSort = 6;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
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
        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

        
        // Define the bulk actions array
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make()
        ];
         // Conditionally add ExportBulkAction
         if (!$isFaculty) {
            $bulkActions[] = ExportBulkAction::make();
        }

        return $table
                ->query(FacilityMonitoring::query()
                ->with(['facility', 'user']) 
                )
            ->description('This page contains the history of facility monitoring. For more information, go to the dashboard to download the user manual.')
            ->columns([
                Tables\Columns\TextColumn::make('monitored_date')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('F j, Y'))
                ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                ->label('Monitored By')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
            
                Tables\Columns\TextColumn::make('facility.name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
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
                 Tables\Actions\BulkActionGroup::make($bulkActions)
                ->label('Actions')
                
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
