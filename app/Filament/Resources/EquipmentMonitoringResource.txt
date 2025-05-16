<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentMonitoringResource\Pages;
use App\Filament\Resources\EquipmentMonitoringResource\RelationManagers;
use App\Models\EquipmentMonitoring;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class EquipmentMonitoringResource extends Resource
{
    protected static ?string $model = EquipmentMonitoring::class;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Monitoring History';
    protected static ?string $navigationLabel = 'Equipment Monitoring';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                //
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
        ->query(EquipmentMonitoring::query()
                ->with(['facility', 'user', 'equipment']) 
                )
            ->description('This page contains the history of equipment monitoring. For more information, go to the dashboard to download the user manual.')
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
                Tables\Columns\TextColumn::make('equipment.brand_name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('facility.name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('equipment.category.description')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('equipment.po_number')
                ->label('PO Number')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('equipment.serial_no')
                ->label('Serial No.')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('equipment.property_no')
                ->label('Property No.')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('equipment.control_no')
                ->label('Control No.')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'working' => 'success',
                        'for repair' => 'warning',
                        'for replacement' => 'primary',
                        'lost' => 'danger',
                        'for disposal' => 'primary',
                        'disposed' => 'danger',
                        default => 'secondary',  

                    })
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    EquipmentMonitoring::query()
                        ->whereNotNull('monitored_date') // Filter out null values
                        ->pluck('monitored_date', 'monitored_date')
                        ->mapWithKeys(function ($date) {
                            // Format date using Carbon
                            return [$date => \Carbon\Carbon::parse($date)->format('F j, Y')];
                        })
                        ->toArray()
                ),
                SelectFilter::make('monitored_by')
                    ->label('Monitored By')
                    ->relationship('user','name'),
                
              
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEquipmentMonitorings::route('/'),
            //'create' => Pages\CreateEquipmentMonitoring::route('/create'),
            //'edit' => Pages\EditEquipmentMonitoring::route('/{record}/edit'),
        ];
    }
}
