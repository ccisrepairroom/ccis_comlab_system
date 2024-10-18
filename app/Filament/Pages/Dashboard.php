<?php

namespace App\Filament\Pages;

use Filament\Pages\Actions\Action; 
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Widgets\OtherWidget;
use App\Filament\Widgets\AllEquipment;
use App\Filament\Widgets\TotalUserWidget;
//use App\Filament\Widgets\AllFacilities;
use App\Filament\Widgets\AllFacility;
use App\Filament\Widgets\AllCategory;
use App\Filament\Widgets\EquipmentCountPerCategory;
use App\Filament\Widgets\EquipmentCountPerStatus;
use App\Filament\Widgets\EquipmentCountPerFacility;
use App\Filament\Widgets\ForDisposal;
use App\Filament\Widgets\Disposed;
use App\Filament\Widgets\PersonLiable;
use App\Filament\Widgets\FacilityPerFloorLevel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;



class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public int | string | array $columnSpan = 3;

    public function getWidgets(): array
    {
        return [
            TotalUserWidget::class,  
            AllEquipment::class,     // w/ facilities, categories
            EquipmentCountPerCategory::class,
            EquipmentCountPerStatus::class,
            EquipmentCountPerFacility::class,
            ForDisposal::class,
            Disposed::class,
            FacilityPerFloorLevel::class,
            PersonLiable::class,
            //AllFacility::class,
           // AllCategory::class,
            // AllFacilities::class,   
           //EquipmentCatStat::class,
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            //TextInput::make('name'),
            DatePicker::make('startDate'),
            DatePicker::make('endDate'),
            //Toggle::make('active'),
        ])->columns(3);
    }

    public function exportToWord()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Example: Add some content to the Word document from widgets
        $section->addText("Dashboard Widgets Summary");

        $totalEquipment = \App\Models\Equipment::count();
        $section->addText("Total Equipment: " . $totalEquipment);
        //$section->addText("Equipment by Category: " . EquipmentCountPerCategory::count());

        // Save the file
        $filename = 'dashboard-widgets-summary.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }


    protected function getActions(): array
    {
        return [
            Action::make('exportToWord')
                ->label('Export to Word')
                ->action('exportToWord')  // Link this to the export function
                ->icon('heroicon-o-document-arrow-down')
        ];
    }
}
