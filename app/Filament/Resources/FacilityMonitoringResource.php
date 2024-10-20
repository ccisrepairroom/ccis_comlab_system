<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityMonitoringResource\Pages;
use App\Filament\Resources\FacilityMonitoringResource\RelationManagers;
use App\Models\Facility;
use App\Models\User;
use App\Models\Critical;
use App\Models\ForRepair;
use App\Models\Equipment;
use App\Models\FacilityMonitoring;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;

class FacilityMonitoringResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
   protected static ?string $navigationGroup = 'Monitoring';
    protected static ?string $navigationLabel = 'Facility Monitoring';
    protected static ?int $navigationSort = 2;

    public static function getSlug(): string
    {
        return 'facility-monitoring'; 
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('connection_type')
                    ->label('Connection Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('facility_type')
                    ->label('Facility Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cooling_tools')
                    ->label('Cooling Tools')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('floor_level')
                    ->label('Floor Level')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('building')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => strip_tags($state)),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make('view_monitoring')
                        ->label('View Records')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->color('info')
                        ->modalHeading('Monitoring Records')
                        ->modalContent(function ($record) {
                            $facilityId = $record->id;

                            

                            $monitorings = FacilityMonitoring::where('facility_id', $facilityId)
                                ->with('user')
                                ->get();
                            // dd($monitorings);                      
                            return view('filament.resources.facility-monitoring-modal', [
                                'monitorings' => $monitorings,
                            ]);
                        }),
                    Tables\Actions\Action::make('Update Status')
                        ->icon('heroicon-o-plus')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-check')
                        ->modalHeading('Add to Monitoring')
                        ->modalDescription('Confirm to add selected items to your Monitoring')
                        
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
                                        ->default(now())
                                        ->disabled()
                                        ->format('Y-m-d'),
                                    Forms\Components\TextInput::make('remarks')
                                        ->default($record->remarks)
                                        ->formatStateUsing(fn($state) => strip_tags($state))
                                        ->label('Remarks'),
                                ]);
                        })
                        ->action(function (array $data, $record) {
                            $data['facility_id'] = $record->id;

                            if (empty($data['monitored_by'])) {
                                $data['monitored_by'] = auth()->user()->id;
                            }

                            if (empty($data['monitored_date'])) {
                                $data['monitored_date'] = now()->format('Y-m-d');
                            }
                            
                            FacilityMonitoring::create($data);

                        
                             // Update the remarks in the Facility model
                            Facility::where('id', $record->id)
                            ->update(['remarks' => $data['remarks']]);


                            Notification::make()
                                ->success()
                                ->title('Success')
                                ->body('Selected items have been added to your monitoring.')
                                ->send();
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            //'edit' => Pages\EditFacilitySummary::route('/{record}/edit'),
        ];
    }
}