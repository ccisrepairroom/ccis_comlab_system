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
            Tables\Actions\BulkAction::make('approve')
            ->label('Approve Selected')
            ->icon('heroicon-o-check')
            ->action(function (Collection $records) {
                // Loop through the selected records and update their status to 'approved'
                foreach ($records as $record) {
                    $record->update(['request_status' => 'approved']);
                }
                Notification::make()
                ->title('Request Approved')
                ->icon('heroicon-o-check')
                ->warning()  // You can also use ->danger(), ->warning(), etc.
                ->body('The selected request(s) have been successfully approved.')
                ->send();
                })
            ->requiresConfirmation() // Optional: You can require confirmation before performing the action
            ->color('warning'),  // Optional: Set color for the button (e.g., 'success', 'primary', etc.)

            Tables\Actions\BulkAction::make('reject')
            ->label('Reject Selected')
            ->icon('heroicon-c-x-circle')
            ->action(function (Collection $records, array $data) {
                $remarks = $data['remarks'] ?? null;
                if (!$remarks) {
                    throw new \Exception('Remarks are required for rejection.');
                }

                foreach ($records as $record) {
                    $record->update([
                        'request_status' => 'rejected',
                        'remarks' => $remarks,  
                    ]);
                }
                Notification::make()
                ->title('Request Rejected')
                ->danger()  
                ->body('The selected request(s) have been rejected.')
                ->send();
            })
            ->form([
                Forms\Components\TextArea::make('remarks')
                    ->label('Remarks')
                    ->required()  
                    ->placeholder('Enter remarks for rejection...')
                    ->rows(4)
            ])
            ->requiresConfirmation() 
            ->color('danger'),  
            Tables\Actions\BulkAction::make('returned')
            ->label('Mark as Returned')
            ->icon('heroicon-o-check')
            ->color('success')
            ->action(function (Collection $records, array $data) {
                $receivedby = $data['received_by'] ?? null;
                if (!$receivedby) {
                    throw new \Exception('Received By is required when returning items.');
                }

                // Get the current date and time for the returned_date
                $returnedDate = now();  // You can also use \Carbon\Carbon::now() if needed

                foreach ($records as $record) {
                    $record->update([
                        'status' => 'returned',
                        'received_by' => $receivedby,
                        'returned_date' => $returnedDate,  // Set the returned_date
                    ]);
                }

                // Send a notification after updating the records
                Notification::make()
                    ->title('Items Returned')
                    ->success()  
                    ->body('The selected items have been returned.')
                    ->send();
            })
            ->form([
                Forms\Components\TextArea::make('received_by')
                    ->label('Received By')
                    ->required()  
                    ->placeholder('Enter details...')
                    ->rows(4)
            ])
            ->color('success'), 
            

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
                    ->label('Date Requested')
                    ->searchable()
                    ->sortable()
                    
                    
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('request_code')
                    ->label('Request Code')    
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('request_status')
                    ->label('Request Status')    
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state)))
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'approved' => 'warning',
                        'pending' => 'info',
                        'rejected' => 'danger',
                        default => 'secondary',  

                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Availability')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => ucfirst(strtolower($state)))
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'returned' => 'success',
                        'unreturned' => 'danger',
                        default => 'secondary',  

                    }),
                    //Tables\Columns\TextColumn::make('request_status'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('borrowed_by')
                    ->label('Borrower')    
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number')    
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('equipment.brand_name')
                    ->label('Requested Equipment')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->toggleable(isToggledHiddenByDefault: false),
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
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Assigned/Requested Facility')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn ($state) => $state ? strtoupper($state) : 'N/A'),

                Tables\Columns\TextColumn::make('equipment.unit_no')
                    ->label('Unit Number')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
               
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

                // Tables\Columns\TextColumn::make('request_form')
                // ->label('Signed Request Form')
                // ->sortable()
                
                // ->toggleable(isToggledHiddenByDefault: false)
                // ->formatStateUsing(fn (string $state): string => basename($state)),

                Tables\Columns\TextColumn::make('purpose')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                ->label('Remarks')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
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
                Tables\Columns\TextColumn::make('returned_date')
                ->label('Date Returned')
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y g:i A'))
                ->toggleable(isToggledHiddenByDefault: false)
                ->sortable(),
                Tables\Columns\TextColumn::make('received_by')
                ->label('Received By')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
               

            ])
            ->defaultSort('created_at', 'desc')
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
