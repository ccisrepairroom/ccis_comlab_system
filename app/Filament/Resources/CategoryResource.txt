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
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    //protected static ?string $navigationGroup = 'Classification';
    protected static ?int $navigationSort = 1;

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
        $isPanelUser = $user && $user->hasRole('panel_user');

         // Define the bulk actions array
         $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
            //Tables\Actions\ExportBulkAction::make()

         ];
                 // Conditionally add ExportBulkAction

            if (!$isPanelUser) {
                $bulkActions[] = ExportBulkAction::make();
            }
            return $table
            ->query(Category::with('user'))
            ->columns([
    
         
                    Tables\Columns\TextColumn::make('description')
                        ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                        ->searchable()
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
                    // Add any filters here if needed
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
