<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\Importable;
use App\Models\SuppliesAndMaterials;
use App\Models\Facility;
use App\Models\Category;
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
    $categoryDescription = trim($row['category_id'] ?? '');


    $facility = $facilityName ? Facility::firstOrCreate(['name' => $facilityName], ['name' => $facilityName]) : null;
    $stockunit = $stockUnitDescription ? StockUnit::firstOrCreate(['description' => $stockUnitDescription], ['description' => $stockUnitDescription]) : null;
    $category = $categoryDescription ? Category::firstOrCreate(['description' => $categoryDescription], ['description' => $categoryDescription]) : null;


     // Prepare data array with null checks and type casting
     $quantity = isset($row['quantity']) ? (is_numeric($row['quantity']) ? (int) $row['quantity'] : null) : null;
     // Ensure stocking_point is a valid numeric value, otherwise set it to null
    $stockingPoint = isset($row['stocking_point']) ? (is_numeric($row['stocking_point']) ? (int) $row['stocking_point'] : null) : null;


    // Prepare data array with null checks
    $data = [
        'item' => $row['item'] ?? null,
        'quantity' => $quantity,
        'stocking_point' => $stockingPoint,
        //'facility_id' => $facility ? $facility->id : null,
        'facility_id' => $this->getFacilityId($row['facility']) ?? null,
        //'category_id' => $facility ? $facility->id : null,
        'category_id' => $this->getCategoryId($row['category']) ?? null,
        'stock_unit_id' => $this->getStockUnitId($row['stock_unit']) ?? null,
        'supplier' => $row['supplier'] ?? null,
        'remarks' => $row['remarks'] ?? null,

    ];
    
    // Define essential fields to check
    $essentialFields = [
         'item',
         'quantity',
         'stocking_point' ,
         'facility_id',
         'category_id',
         'stock_unit_id',
        'supplier',
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

          /**
     * Get Facility ID based on the location provided in the row
     *
     * @param string|null $location
     * @return int|null
     */
        public function getFacilityId($location)
        {
            // Check if location exists, else return null
            if (!$location) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            /*$facility = Facility::where('name', $location)->first();
            return $facility ? $facility->id : null;*/

            $facility = Facility::firstOrCreate(['name' => $location], ['name' => $location]);
            return $facility->id; 
        }

        public function getCategoryId($category)
        {
            // Check if location exists, else return null
            if (!$category) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            /*$category = Category::where('description', $category)->first();
            return $category ? $category->id : null;*/

            $category = Category::firstOrCreate(['description' => $category], ['description' => $category]);
            return $category->id;
        }
        public function getStockUnitId($stockunit)
        {
            // Check if location exists, else return null
            if (!$stockunit) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            $stockunit = StockUnit::firstOrCreate(['description' => $stockunit], ['description'=> $stockunit]);
            return $stockunit ? $stockunit->id : null;
        }
}
    