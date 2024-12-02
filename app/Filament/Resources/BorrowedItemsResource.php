<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowedItemsResource\Pages;
use App\Filament\Resources\BorrowedItemsResource\RelationManagers;
use App\Models\BorrowedItems;
use App\Models\RequestList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;



class BorrowedItemsResource extends Resource
{
    protected static ?string $model = BorrowedItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static bool $isLazy = false;
    protected static ?string $navigationGroup = 'Borrowing';
    protected static ?string $navigationLabel = 'Borrowed Items';
    protected static ?int $navigationSort = 2;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }
    protected static ?string $pollingInterval = '1s';
    public static function getNavigationBadge(): ?string
    {
        // Check if the user is authenticated and has the 'panel_user' role
        if (Auth::check() && Auth::user()->hasRole('panel_user')) {
            // Count only the records where 'user_id' matches the logged-in user's ID
            return static::getModel()::where('user_id', Auth::id())->count();
        }

        // If the user is not a 'panel_user', return the total count
        return static::getModel()::count();
    }
    /*
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(auth()->id()),
                Forms\Components\Select::make('borrowed_by')
                    ->required(),
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'brand_name')
                    ->nullable(),
                Forms\Components\Select::make('facility_id')
                    ->relationship('facility', 'name')
                    ->nullable(),
                Forms\Components\TextInput::make('request_status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('request_form')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('borrowed_date')
                    ->required()
                    ->disabled()
                    ->default(fn() => now()->format('Y-m-d'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('returned_date')
                    ->required()
                    ->maxLength(255),
            ]);
    }*/

    public static function table(Tables\Table $table): Tables\Table
    {

        $user = auth()->user();
        $isFaculty = $user && $user->hasRole('faculty');

         // Define the bulk actions array
         $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            //Tables\Actions\ExportBulkAction::make()

         ];
                 // Conditionally add ExportBulkAction

            if (!$isFaculty) {
                $bulkActions[] = ExportBulkAction::make();
            }
            return $table
            ->query(BorrowedItems::with('user'))
            ->description('This page contains the list of all the requested/borrowed equipment and facilities.
            For more information, go to the dashboard to download the user manual.')
            ->columns([

       /* return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Check if the authenticated user is a 'panel_user'
                if (Auth::user() && Auth::user()->hasRole('panel_user')) {
                    // Filter the records to only show those that belong to the current logged-in user
                    $query->where('user_id', Auth::id());
                }
            })*/
                Tables\Columns\TextColumn::make('borrowed_date')
                    ->label('Date Created')
                    ->searchable()
                    ->sortable()
                    
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                    
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('borrowed_by')
                    ->label('Borrowed By')    
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('equipment.brand_name')
                    ->label('Requested Equipment')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Assigned/Requested Facility')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn ($state) => $state ? strtoupper($state) : 'N/A'),

                Tables\Columns\TextColumn::make('equipment.unit_no')
                    ->label('Unit Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.category.description')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > $column->getCharacterLimit() ? $state : null;
                    })
                    ->searchable(),
                /*Tables\Columns\TextColumn::make('equipment.status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false)

                    ->searchable()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'working' => 'success',
                        'for repair' => 'warning',
                        'for replacement' => 'primary',
                        'lost' => 'danger',
                        'for disposal' => 'primary',
                        'disposed' => 'danger',
                        'borrowed' => 'indigo',
                        default=>'default',
                    }),*/
                Tables\Columns\TextColumn::make('equipment.control_no')
                    ->label('Control Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.serial_no')
                    ->label('Serial Number')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.property_no')
                    ->label('Property Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
               
                Tables\Columns\TextColumn::make('equipment.person_liable')
                    ->label('Person_liable')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('request_form')
                ->label('Signed Request Form')
                ->sortable()
                
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn (string $state): string => basename($state)),

                Tables\Columns\TextColumn::make('purpose')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('start_date_and_time_of_use')
                ->searchable()
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('end_date_and_time_of_use')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->sortable(),
                Tables\Columns\TextColumn::make('expected_return_date')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Availability')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                    //Tables\Columns\TextColumn::make('request_status'),
                Tables\Columns\TextColumn::make('remarks')
                ->label('Remarks')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
                Tables\Columns\TextColumn::make('returned_date')
                ->label('Date Returned')
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('received_by')
                ->label('Received By')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
               

                // \EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn::make("approvalStatus.status"),
            ])
            ->filters([
                SelectFilter::make('borrowed_date')
                ->label('Date Created')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('borrowed_date') // Filter out null values
                        ->pluck('borrowed_date', 'borrowed_date')
                        ->mapWithKeys(function ($date) {
                            // Format date using Carbon
                            return [$date => \Carbon\Carbon::parse($date)->format('F j, Y')];
                        })
                        ->toArray()
                ),
                SelectFilter::make('borrowed_by')
                ->label(' Borrowed By')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('borrowed_by') // Filter out null values
                        ->distinct()
                        ->select('borrowed_by')
                        ->pluck('borrowed_by', 'borrowed_by')
                        ->toArray()
                ),
                SelectFilter::make('status')
                ->label(' Status')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('status') // Filter out null values
                        ->distinct()
                        ->select('status')
                        ->pluck('status', 'status')
                        
                        ->toArray()
                ),
                SelectFilter::make('equipment.brand_name')
                    ->label('Requested Equipment')
                    
                    ->options(
                        BorrowedItems::query()
                            ->whereNotNull('equipment_id') // Filter out null values
                            ->pluck('equipment_id', 'equipment_id')
                            
                            ->toArray()
                    ),
                    SelectFilter::make('facility.name')
                    ->label('Requested Facility')
                    
                    ->options(
                        BorrowedItems::query()
                            ->whereNotNull('facility_id') // Filter out null values
                            ->pluck('facility_id', 'facility_id')
                            
                            ->toArray()
                    ),
                
                SelectFilter::make('start_date_and_time_of_use')
                ->label(' Start Date and Time of Use')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('start_date_and_time_of_use') // Filter out null values
                        ->distinct()
                        ->select('start_date_and_time_of_use')
                        ->pluck('start_date_and_time_of_use', 'start_date_and_time_of_use')
                        ->toArray()
                ),
                SelectFilter::make('end_date_and_time_of_use')
                ->label(' End Date and Time of Use')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('end_date_and_time_of_use') // Filter out null values
                        ->distinct()
                        ->select('end_date_and_time_of_use')
                        ->pluck('end_date_and_time_of_use', 'end_date_and_time_of_use')
                        ->toArray()
                ),
                SelectFilter::make('expected_return_date')
                ->label('Expected Return Date')
                ->options(
                    BorrowedItems::query()
                        ->whereNotNull('expected_return_date') // Filter out null values
                        ->distinct()
                        ->select('expected_return_date')
                        ->pluck('expected_return_date', 'expected_return_date')
                        ->toArray()
                ),
            ])
            ->actions([
                // ...\EightyNine\Approvals\Tables\Actions\ApprovalActions::make(
                //     // define your action here that will appear once approval is completed
                //     Action::make("Done"),
                //     [
                //         Tables\Actions\EditAction::make(),
                //         Tables\Actions\ViewAction::make()
                //     ]
                // ),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('viewPdf')
                        ->label('Download Form')
                        ->icon('heroicon-o-document-text')
                        ->action(function ($record) {
                            // Generate the download URL
                            $url = asset('storage/' . $record->request_form);

                            // Redirect to the PDF URL to trigger the download
                           return redirect()->away($url);
                        })
                        ->color('info'),
                    Tables\Actions\Action::make('updateStatus')
                        ->label('Update Return Status')
                        ->color('danger')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('status')
                                        ->label('Availability')
                                        ->options([
                                            'Returned' => 'Returned',
                                            'Unreturned' => 'Unreturned',
                                        ])
                                        ->reactive()
                                        ->default('Unreturned')
                                        ->default(fn($record) => $record->status)
                                        ->required(),
                                    /*Forms\Components\Select::make('request_status')
                                        ->label('Request Status')
                                        ->options([
                                            'Pending' => 'Pending',
                                            'Approved' => 'Approved',
                                            'Rejected' => 'Rejected',
                                            // Add more options if needed
                                        ])
                                        ->reactive()
                                        ->required()
                                        ->disabled(fn(callable $get) => $get('record.request_status') === 'Approved')
                                        ->default(fn($record) => $record->request_status),*/
                                    
                                    Forms\Components\DateTimePicker::make('returned_date')
                                        ->label('Returned Date')
                                        ->visible(fn(callable $get) => $get('status') === 'Returned')
                                        ->required(fn(callable $get) => $get('status') === 'Returned')
                                        ->placeholder('Select return date')
                                        ->default(fn() => now('Asia/Manila')),
                                        
  
                                    Forms\Components\TextInput::make('remarks')
                                        ->default(fn($record) => $record->remarks !== 'test' ? $record->remarks : '')
                                        ->visible(fn(callable $get) => $get('status') === 'Returned')
                                        ->required(fn(callable $get) => $get('status') === 'Returned')
                                        ->columnSpanFull(),
                                
                                Forms\Components\TextInput::make('received_by')
                                ->default(fn($record) => $record->received_by !== 'test' ? $record->received_by : '')
                                ->visible(fn(callable $get) => $get('status') === 'Returned')
                                ->required(fn(callable $get) => $get('status') === 'Returned')
                                ->columnSpanFull()
                        ]),
                        ])
                        ->action(function ($record, $data) {
                            // Log the data for debugging purposes
                            Log::info('Updating record', [
                                'record_id' => $record->id,
                                'status' => $data['status'],
                                'returned_date' => $data['returned_date']?? null,
                                'remarks' => $data['remarks']?? null,
                                'received_by' => $data['received_by']?? null,
                            ]);

                            // Update the record with the new status, returned date, and remarks
                            $record->update([
                                //'request_status' => $data['request_status'],
                                'status' => $data['status'],
                                'returned_date' => $data['status'] === 'Returned' ? $data['returned_date'] : null,
                                'remarks' => $data['status'] === 'Returned' ? $data['remarks'] : null,
                                'received_by' => $data['status'] === 'Returned' ? $data['received_by'] : null,
                            ]);

                            // Check if the record was updated successfully
                            if ($record->wasChanged()) {
                                Notification::make()
                                    ->title('Return Status Updated')
                                    ->success()
                                    ->body('The return status has been updated successfully.')
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('No Changes Detected')
                                    ->warning()
                                    ->body('The return status did not change.')
                                    ->send();
                            }
                        })
                        ->hidden(fn () => $isFaculty)
                        ->modalHeading('Update Equipment Return Status')
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions')
            ]);
            }        
                
    // protected static function getTableQuery(): Builder
    // {
    //     $query = parent::getTableQuery();
    //     $user = Auth::user();

    //     // Only show borrows associated with the logged-in user
    //     if ($user->hasRole('panel_user')) { // Replace 'panel_user' with the actual role
    //         $query->where('user_id', $user->id);
    //     }

    //     return $query;
    // }
            
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowedItems::route('/'),
            'create' => Pages\CreateBorrowedItems::route('/create'),
            //'edit' => Pages\EditBorrow::route('/{record}/edit'),
        ];
    }
}
