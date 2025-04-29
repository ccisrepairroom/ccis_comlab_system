<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestsResource\Pages;
use App\Filament\Resources\RequestsResource\RelationManagers;
use App\Models\Request;
use App\Models\User;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;



class RequestsResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus';

   protected static function generateRequestCode()
{
    $latestCode = Request::max('request_code'); // Get the highest request_code

    if (!$latestCode) {
        return '00001'; // Default if no records exist
    }

    // Increment the numeric value and pad with leading zeros
    return str_pad((int) $latestCode + 1, 5, '0', STR_PAD_LEFT);
}


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Request Information')
                ->schema([
                        Forms\Components\TextInput::make('request_code')  
                            ->label('Request Code')
                            ->default(fn () => self::generateRequestCode())
                            ->readOnly()
                            ->required(),
                        Forms\Components\TextInput::make('name')  
                            ->label('Name')
                            ->required(),
                            
                        Forms\Components\ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'returned' => 'Returned',
                                'unreturned' => 'Unreturned',
                            ])
                            ->colors([
                                'pending' => 'info',
                                'returned' => 'success',
                                'unreturned' => 'danger',
                                
                            ])
                            ->icons([
                                'pending' => 'heroicon-m-arrow-path',
                                'returned' => 'heroicon-m-check-badge',
                                'unreturned' => 'heroicon-m-x-circle',
                            ]),

                             Forms\Components\TextInput::make('total_request')  
                            ->label('Total Request')
                            ->required(),
                
                ])
                ->columns(2),

                Section::make('Request Items')
                ->schema([
                    Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('equipment_id')
                            ->relationship('equipment', 'brand_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ])
                ])
                

            ]);
           
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('request_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_request')
                    ->label('Total Request')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('request_status')
                    ->sortable()
                    ->searchable()
                    ->toggleable()

                    
                    ->options([
                        'pending' => 'Pending',
                        'returned' => 'Returned',
                        'unreturned' => 'Unreturned',
                    ]), 

            ])
            ->filters([
                //
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
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequests::route('/create'),
            'edit' => Pages\EditRequests::route('/{record}/edit'),
        ];
    }
}
