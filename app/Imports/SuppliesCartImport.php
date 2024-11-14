<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\SuppliesAndMaterials;
use App\Models\Facility;
use App\Models\StockUnit;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SuppliesCartImport implements ToModel, WithHeadingRow

{use Importable;

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
        $stockunit = $stockUnitDescription ? StockUnit::firstOrCreate(['description' => $stockUnitDescription], ['description' => $stockUnitDescription]) : null;
        
    // Prepare data array with null checks
$data = [
        'requested_by' => $row['requested_by'] ?? null,
        //'user_id' => $user ? $user->id : null,
        'supplies_and_materials_id' => $supplies_and_materials ? $supplies_and_materials->id : null,
        'facility_id' => $facility ? $facility->id : null,
        'available_quantity' => $row['available_quantity'] ?? null,
        'quantity_requested' => $row['quantity_requested'] ?? null,
        'stock_unit_id' => $stockunit  ? $stockunit ->id : null,
        'action_date' => $row['action_date'] ?? null,
        'remarks' => $row['remarks'] ?? null,

    ];
    
    // Define essential fields to check
    $essentialFields = [
         'requested_by',
         //'user_id',
         'supplies_and_materials_id',
         'facility_id' ,
         'available_quantity',
         'quantity_requested',
         'action_date',
         'remarks',



        ];
    }
}