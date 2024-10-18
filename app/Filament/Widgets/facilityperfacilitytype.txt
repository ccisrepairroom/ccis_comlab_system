<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Facility;

class FacilityPerFacilityType extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $facilityTypeCounts = Facility::selectRaw('facility_type, COUNT(*) as count')
            ->groupBy('facility_type')
            ->pluck('count', 'facility_type')
            ->toArray();

        $facilityTypes = Facility::select('facility_type')
            ->distinct()
            ->pluck('facility_type')
            ->toArray();

        $data = [];
        foreach ($facilityTypes as $type) {
            $data[] = $facilityTypeCounts[$type] ?? 0; // Default to 0 if facility type is not found
        }


        return [
            'datasets' => [
                [
                    'label' => 'Number of Facilities',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $facilityTypes,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
