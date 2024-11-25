<?php
namespace App\Imports;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\Category;
use App\Models\StockUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
{
    $userId = auth()->id(); 
    // Trim and retrieve related models
    $facilityName = trim($row['facility_id'] ?? '');
    $categoryDescription = trim($row['category_id'] ?? '');
    //$stockUnitDescription = trim($row['stock_unit_id'] ?? '');
  //  \Log::info('Facility ID:', ['facility_id' => $facilityName]);
    //\Log::info('Category ID:', ['category_id' => $categoryDescription]);
 
    $facility = $facilityName ? Facility::firstOrCreate(['name' => $facilityName], ['name' => $facilityName]) : null;
    $category = $categoryDescription ? Category::firstOrCreate(['description' => $categoryDescription], ['description' => $categoryDescription]) : null;
    //$stock_unit = $stockUnitDescription ? StockUnit::firstOrCreate(['description' => $stockUnitDescription], ['description' => $stockUnitDescription]) : null;

    // Prepare data array with null checks
    $data = [
       
        'unit_no' => $row['unit_number'] ?? null,
        'brand_name' => $row['brand_name'] ?? null,
        'description' => $row['description'] ?? null,
        'facility_id' => $this->getFacilityId($row['facility']) ?? null,
        //'facility_id' => $facility ? $facility->id : null,
        'category_id' =>  $this->getCategoryId($row['category']) ?? null,
        'status' => $row['status'] ?? null,
        'date_acquired' => $row['date_acquired'] ?? null,
        'supplier' => $row['supplier'] ?? null,
        'amount' => $row['amount'] ?? null,
        'estimated_life' => $row['estimated_life'] ?? null,
        'item_no' => $row['item_number'] ?? null,
        'po_number' => $row['po_number'] ?? null,
        'property_no' => $row['property_number'] ?? null,
        'control_no' => $row['control_number'] ?? null,
        'serial_no' => $row['serial_number'] ?? null,
        //'no_of_stocks' => $row['no_of_stocks'] ?? null,
        //'restocking_point' => $row['restocking_point'] ?? null,
        //'stock_unit_id' => $stock_unit ? $stock_unit->id : null,
        'person_liable' => $row['person_liable'] ?? null,
        'user_id' => $userId ?? null, 
        'remarks' => $row['remarks'] ?? null,
    ];

    // Define essential fields to check
    $essentialFields = [
        'unit_no',
        'description',
        'brand_name',
        'description',
        'facility_id',
        'category_id',
        'status',
        'date_acquired',
        'supplier',
        'amount',
        'estimated_life',
        'item_no',
        'po_number',
        'property_no',
        'control_no',
        'serial_no',
        //'no_of_stocks',
        //'restocking_point',
        //'stock_unit_id',
        'person_liable',
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
    return new Equipment($data);
}
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
    }
