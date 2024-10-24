<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentMonitoringResource\Pages;
use App\Models\EquipmentMonitoring;
use App\Models\StockMonitoring;

use App\Models\MonitoringHistory;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Critical;
use App\Models\ForRepair;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


class EquipmentMonitoringResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationGroup = 'Monitoring';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'Equipment Monitoring';
    protected static ?int $navigationSort = 2;

    public static function getSlug(): string
    {
        return 'equipment-monitoring'; 
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit_no')
                    ->label('Unit no.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('facility.name')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(fn(string $state): string => match ($state) {
                        'Working' => 'success',
                        'For Repair' => 'warning',
                        'For Replacement' => 'primary',
                        'Lost' => 'danger',
                        'For Disposal' => 'primary',
                        'Disposed' => 'danger',
                        'Borrowed' => 'indigo',
                    }),
                Tables\Columns\TextColumn::make('date_acquired')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estimated_life')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('item_no')
                    ->label('Item no.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('property_no')
                    ->label('Property no.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('control_no')
                    ->label('Control no.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial_no')
                    ->label('Serial no.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_of_stocks')
                    ->label('No. of Stocks')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $description = optional($record->stockunit)->description;
                        return $record->no_of_stocks . ($description ? " ($description)" : '');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('restocking_point')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $description = optional($record->stockunit)->description;
                        return $record->restocking_point . ($description ? " ($description)" : '');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('person_liable')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remarks')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make('view_monitoring')
                        ->label('View Equipment Records')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->color('info')
                        ->modalHeading('Monitoring Records')
                        ->modalContent(function ($record) {
                            $equipmentId = $record->id;
                            $monitorings = EquipmentMonitoring::with('equipment.facility', 'user')
                                ->where('equipment_id', $equipmentId)
                                ->get();
                            return view('filament.resources.equipment-monitoring-modal', [
                                'monitorings' => $monitorings,
                            ]);
                        }),
                       
                    
                        /*->action(function ($record) {
                            $stockHistory = \App\Models\StockMonitoring::where('equipment_id', $record->id)
                                ->with('user') // Assuming 'monitored_by' is related to the 'users' table
                                ->orderBy('deducted_at', 'desc')
                                ->get();
                    
                            return view('filament.resources.stock-monitoring-modal', [
                                'stockHistory' => $stockHistory,
                            ]);
                        }),*/
                        
                        

                    Tables\Actions\Action::make('Update Status')
                        ->icon('heroicon-o-plus')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-check')
                        ->modalHeading('Update Equipment Status')
                        ->modalDescription('Confirm to update equipment status')
                        ->form(function (Forms\Form $form, $record) {
                            return $form
                                ->schema([
                                    Forms\Components\Select::make('monitored_by')
                                        ->label('Monitored By')
                                        ->options(User::all()->pluck('name', 'id'))
                                        ->default(auth()->user()->id)
                                        ->disabled()
                                        ->required(),
                                    Forms\Components\DatePicker::make('monitored_date')
                                        ->label('Monitoring Date')
                                        ->required()
                                        ->disabled()
                                        ->default(now())
                                        ->format('Y-m-d'),

                                    Forms\Components\Select::make('status')
                                        ->required()
                                        ->options([
                                            'Working' => 'Working',
                                            'For Repair' => 'For Repair',
                                            'For Replacement' => 'For Replacement',
                                            'Lost' => 'Lost',
                                            'For Disposal' => 'For Disposal',
                                            'Disposed' => 'Disposed',
                                            'Borrowed' => 'Borrowed',
                                        ])
                                        ->default($record->status)
                                        ->native(false),
                                    Forms\Components\Select::make('facility_id')
                                        ->label ('New Assigned Facility')
                                        ->relationship('facility', 'name')
                                        ->default($record->facility_id)
                                        ->required(),
                                    
                                    Forms\Components\TextInput::make('remarks')
                                        ->default($record->remarks)
                                        ->formatStateUsing(fn($state) => strip_tags($state))
                                        ->label('Remarks'),
                                ]);
                            return $form->schema([
                                Forms\Components\Select::make('monitored_by')
                                    ->label('Monitored By')
                                    ->options(User::all()->pluck('name', 'id'))
                                    ->default(auth()->user()->id)
                                    ->disabled()
                                    ->required(),
                                Forms\Components\DatePicker::make('monitored_date')
                                    ->label('Monitoring Date')
                                    ->required()
                                    ->default(now())
                                    ->format('Y-m-d'),
                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'Working' => 'Working',
                                        'For Repair' => 'For Repair',
                                        'For Replacement' => 'For Replacement',
                                        'Lost' => 'Lost',
                                        'For Disposal' => 'For Disposal',
                                        'Disposed' => 'Disposed',
                                        'Borrowed' => 'Borrowed',
                                    ])
                                    ->default($record->status)
                                    ->native(false),
                                Forms\Components\Select::make('facility_id')
                                    ->relationship('facility', 'name')
                                    ->default($record->facility_id)
                                    ->required(),
                                Forms\Components\TextInput::make('remarks')
                                    ->default($record->remarks)
                                    ->formatStateUsing(fn($state) => strip_tags($state))
                                    ->label('Remarks'),
                            ]);
                        })
                        ->action(function (array $data, $record) {
                            $data['equipment_id'] = $record->id;
    
                            if (empty($data['monitored_by'])) {
                                $data['monitored_by'] = auth()->user()->id;
                            }
    
                            if (empty($data['monitored_date'])) {
                                $data['monitored_date'] = now()->format('Y-m-d');
                            }
    
                            EquipmentMonitoring::create($data);
    
                            $record->update([
                                'status' => $data['status'],
                                'facility_id' => $data['facility_id'],
                                'remarks' => $data['remarks'],
                            ]);
    
                            Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Status of the selected item/s have been updated.')
                                ->send();
                        }),

                        Tables\Actions\Action::make('AdjustStock')
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-pencil')
                        ->modalHeading('Adjust Stock')
                        ->modalDescription('Enter the quantity to adjust stocks.')
                        ->form(function (Forms\Form $form, $record) {
                            return $form->schema([
                                Forms\Components\Select::make('action_type')
                                    ->label('Action Type')
                                    ->options([
                                        'add' => 'Add Stock',
                                        'deduct' => 'Deduct Stock',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue($record->no_of_stocks + 100) // Adjust maxValue for adding
                                    ->hint("Available stock: {$record->no_of_stocks}")
                                    ->required(),
                            ]);
                        })
                        ->action(function (array $data, $record) {
                            if ($data['action_type'] === 'deduct') {
                                $newStock = $record->no_of_stocks - $data['quantity'];
                                if ($newStock < 0) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Error')
                                        ->body('Insufficient stock. Cannot deduct more than available stock.')
                                        ->send();
                                    return;
                                }
                            } else {
                                $newStock = $record->no_of_stocks + $data['quantity'];
                            }
                    
                            \App\Models\StockMonitoring::create([
                                'equipment_id' => $record->id,
                                'facility_id' => $record->facility_id,
                                'monitored_by' => auth()->user()->id,
                                'no_of_stocks' => $record->no_of_stocks,
                                'no_of_stocks_deducted' => $data['action_type'] === 'deduct' ? $data['quantity'] : 0,
                                'no_of_stocks_added' => $data['action_type'] === 'add' ? $data['quantity'] : 0,
                                'stocks_left' => $newStock,
                                'deducted_at' => $data['action_type'] === 'deduct' ? now() : null,
                                'added_at' => $data['action_type'] === 'add' ? now() : null,
                            ]);
                    
                            $record->update(['no_of_stocks' => $newStock]);
                    
                            Notification::make()
                                ->success()
                                ->title('Stock Adjusted')
                                ->body('Stock has been successfully adjusted.')
                                ->send();
                        }),
                    
                   
                        
                        /*Tables\Actions\Action::make('add_to_monitoring_history')
                        ->label('Add to Monitoring Histories')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Add Equipment to Monitoring History')
                        ->modalDescription('Are you sure you want to add this equipment to monitoring history?')
                        ->action(function ($record) {
                            $equipment = Equipment::where('id', $record->id)->first();
                           // $equipment = Equipment::find($record->id);
                            
                             Save to the Critical table with status set to "Critical"
                            MonitoringHistory::create([
                                'user_id' => auth()->id(),               // The ID of the current user
                                'equipment_id' => $equipment->id,        // The ID of the equipment
                                'facility_id' => $equipment->facility_id, // The ID of the facility associated with the equipment
                                'category_id' => $equipment->category_id,
                                'stock_unit_id' => $equipment->stock_unit_id,
                                'actions_taken' => '',
                                'actions_taken' => '',
                                'remarks' => '',       // Remarks or reason for marking as for repair
                            ]);*/
                            /*\DB::table('monitoring_history')->insert([
                                'user_id' => auth()->id(),
                                'equipment_id' => $equipment->id,
                                'facility_id' => $equipment->facility_id,
                                'category_id' => $equipment->category_id,
                                'stock_unit_id' => $equipment->stock_unit_id,
                                //'equipment.status' =>$equipment->status,
                                'actions_taken' => '',
                                'actions_taken_date' => now()->format('Y-m-d'),
                                'remarks' => '',
                                'created_at' => now(),
                                //'updated_at' => now(),
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Add to Monitoring Histories')
                                ->body('Equipment has been added to monitoring histories.')
                                ->send();
                        }),*/
                        /*Tables\Actions\Action::make('mark_as_for_repair')
                            ->label('Mark as For Repair')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->color('warning')
                            ->requiresConfirmation()
                            ->modalHeading('Mark Equipment as For Repair')
                            ->modalDescription('Are you sure you want to mark this equipment as for repair?')
                            ->action(function ($record) {
                                $equipment = Equipment::find($record->id);

                                // Save to the Critical table with status set to "Critical"
                                ForRepair::create([
                                    'user_id' => auth()->id(),               // The ID of the current user
                                    'equipment_id' => $equipment->id,        // The ID of the equipment
                                    'facility_id' => $equipment->facility_id, // The ID of the facility associated with the equipment
                                    'category_id' => $equipment->category_id,
                                    'serial_no' => $equipment->serial_no,
                                    'status' => 'Critical',                  // Set the status to "For repair"
                                    'remarks' => 'Marked as critical',       // Remarks or reason for marking as for repair
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Marked as For Repair')
                                    ->body('Equipment has been marked as for repair.')
                                    ->send();
                            }),
                        Tables\Actions\Action::make('mark_as_critical')
                        ->label('Mark Stock as Critical')
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Mark Equipment Stock as Critical')
                        ->modalDescription('Are you sure you want to mark this equipment stock as critical?')
                        ->action(function ($record) {
                            $equipment = Equipment::find($record->id);
        
                            // Save to the Critical table with status set to "Critical"
                            Critical::create([
                                'user_id' => auth()->id(),               // The ID of the current user
                                'equipment_id' => $equipment->id,        // The ID of the equipment
                                'facility_id' => $equipment->facility_id, // The ID of the facility associated with the equipment
                                'category_id' => $equipment->category_id,
                                'status' => 'Critical',                  // Set the status to "Critical"
                                'remarks' => 'Marked as critical',       // Remarks or reason for marking as critical
                            ]);
        
                            Notification::make()
                                ->success()
                                ->title('Marked Stock as Critical')
                                ->body('Equipment stock has been marked as critical and saved to the Critical table.')
                                ->send();
                        }),*/
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentMonitorings::route('/'),
            'create' => Pages\CreateEquipmentMonitoring::route('/create'),
        ];
    }
}
