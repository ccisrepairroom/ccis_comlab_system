<?php
namespace App\Filament\Resources;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
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

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    //protected static ?string $navigationGroup = 'Classification';
    protected static ?int $navigationSort = 1;
    protected static ?string $pollingInterval = '1s';
    protected static bool $isLazy = false;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->placeholder('Example: Keyboard, Mouse, Door')
                    ->maxLength(255),
            ]);
    }

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
            ->query(Category::with('user'))
            ->description('This page contains all the equipment and supplies categories. For more information, go to the dashboard to download the user manual.')
            ->columns([
    
         
                    Tables\Columns\TextColumn::make('description')
                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->sortable(),
                        Tables\Columns\TextColumn::make('created_at')
                        ->searchable()
                        ->sortable()
                        ->formatStateUsing(function ($state) {
                            // Format the date and time
                            return $state ? $state->format('F j, Y h:i A') : null;
                        })
                        ->toggleable(isToggledHiddenByDefault: true),
                     
                ])
                ->filters([
                    /*SelectFilter::make('created_at')
                ->label('Created At')
                ->options(
                    Category::query()
                        ->whereNotNull('created_at') // Filter out null values
                        ->get(['created_at']) // Fetch the 'created_at' values
                        ->mapWithKeys(function ($user) {
                            $date = $user->created_at; // Access the created_at field
                            $formattedDate = \Carbon\Carbon::parse($date)->format('F j, Y');
                            return [$date->toDateString() => $formattedDate]; // Use string representation as key
                        })
                        ->toArray()
                ),*/
                ])
                ->actions([
                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\EditAction::make(),
                    ]),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make($bulkActions)
                    ->label('Actions')
                ]); // Pass the bulk actions array here
        }
        


       

    public static function getRelations(): array
    {
        return [
            // Define any relationships here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            //'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
