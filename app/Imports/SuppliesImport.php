<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\Importable;
use App\Models\SuppliesAndMaterials;
use App\Models\Facility;
use App\Models\StockUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliesImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
public function model(array $row){
    $userId = auth()->id(); 
    // Trim and retrieve related models
    $facilityName = trim($row['facility_id'] ?? '');
    $stockUnitDescription = trim($row['stock_unit_id'] ?? '');

    $facility = $facilityName ? Facility::firstOrCreate(['name' => $facilityName], ['name' => $facilityName]) : null;
    $stockUnit = $stockUnitDescription ? StockUnit::firstOrCreate(['description' => $stockUnitDescription], ['description' => $stockUnitDescription]) : null;

// Prepare data array with null checks
$data = [
        'item' => $row['item'] ?? null,
        'quantity' => $row['quantity'] ?? null,
        'stocking_point' => $row['stocking_point'] ?? null,
        'facility_id' => $facility ? $facility->id : null,
        'stock_unit_id' => $stock_unit ? $stock_unit->id : null,
        'remarks' => $row['remarks'] ?? null,

    ];
    
    // Define essential fields to check
    $essentialFields = [
         'item',
         'quantity',
         'stocking_point' ,
         'facility_id',
         'stock_unit_id',
         'remarks',


        ];

         // Extract only the essential fields
         $filteredData = array_intersect_key($data, array_flip($essentialFields));

        // Check if any of the essential fields have meaningful data
        if (!array_filter($filteredData, fn($value) => !is_null($value) && $value !== '')) {
            // If the row is blank, return null to skip insertion
            return null;
        }

        // Create and return new Equipment instance if the row has data
        return new SuppliesAndMaterials($data);
        }
    }