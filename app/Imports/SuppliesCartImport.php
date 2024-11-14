<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\SuppliesAndMaterials;
use App\Models\Facility;
use App\Models\StockUnit;
use App\Models\User;
use App\Models\SuppliesCart;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;


class SuppliesCartImport implements ToModel, WithHeadingRow

{
    protected $userId;

    

    // Add a constructor to pass user ID
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    
    use Importable;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row){

        $userId = auth()->id(); 

       // Handle various date formats for 'date_requested'
       $dateRequested = null;
       if (!empty($row['date_requested'])) {
           try {
               // Attempt to parse using Carbon and specific formats
               $dateRequested = Carbon::createFromFormat('F j, Y', $row['date_requested'])->format('Y-m-d');
           } catch (\Exception $e) {
               try {
                   $dateRequested = Carbon::parse($row['date_requested'])->format('Y-m-d');
               } catch (\Exception $e) {
                   $dateRequested = null;
               }
           }
       }

        // Trim and retrieve related models
        $facilityName = trim($row['location'] ?? '');
        $stockUnitDescription = trim($row['stock_unit_id'] ?? '');
        $categoryDescription = trim($row['category'] ?? '');
        $supplies_and_materialsItem = trim($row['item'] ?? '');


    
        $facility = $facilityName ? Facility::firstOrCreate(['name' => $facilityName], ['name' => $facilityName]) : null;
        $stockunit = $stockUnitDescription ? StockUnit::firstOrCreate(['description' => $stockUnitDescription], ['description' => $stockUnitDescription]) : null;
        $category = $categoryDescription ? Category::firstOrCreate(['description' => $categoryDescription], ['description' => $categoryDescription]) : null;
        $supplies_and_materials = $supplies_and_materialsItem ? SuppliesAndMaterials::firstOrCreate(['item' => $supplies_and_materialsItem], ['item' => $supplies_and_materialsItem]) : null;

        
        $availableQuantity = isset($row['available_quantity']) ? (is_numeric($row['available_quantity']) ? (int) $row['available_quantity'] : null) : null;
        $quantityRequested = isset($row['quantity_requested']) ? (is_numeric($row['quantity_requested']) ? (int) $row['quantity_requested'] : null) : null;


        // Prepare data array with null checks
    $data = [
        'requested_by' => $row['requested_by'] ?? null,
        //'user_id' => $user ? $user->id : null,
        //'supplies_and_materials_id' => $supplies_and_materials ? $supplies_and_materials->id : null,
        'supplies_and_materials_id' => $this->getSuppliesAndMaterialsId($row['item']) ?? null,
        //'facility_id' => $facility ? $facility->id : null,
        'facility_id' => $this->getFacilityId($row['location']) ?? null,
        'category_id' => $this->getCategoryId($row['category']) ?? null,
        //'available_quantity' => $row['available_quantity'] ?? null,
        'available_quantity' => $availableQuantity,
        'quantity_requested' => $quantityRequested,
        //'quantity_requested' => $row['quantity_requested'] ?? null,
        'stock_unit_id' => $stockunit  ? $stockunit ->id : null,
        'date_requested' => $row['dateRequested'] ?? null,
        'remarks' => $row['remarks'] ?? null,
        'user_id' => $this->userId, 

    ];
    
    // Define essential fields to check
    $essentialFields = [
         'requested_by',
         'user_id',
         'supplies_and_materials_id',
         'facility_id' ,
         'available_quantity',
         'quantity_requested',
         'date_requested',
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
            return new SuppliesCart($data);
            }

            /**
         * Get Facility ID based on the location provided in the row
         *
         * @param string|null $location
         * @return int|null
         */

    public function getSuppliesAndMaterialsId($supplies_and_materials)
        {
            // Check if location exists, else return null
            if (!$supplies_and_materials) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            $supplies_and_materials = SuppliesAndMaterials::where('item', $supplies_and_materials)->first();
            return $supplies_and_materials ? $supplies_and_materials->id : null;
        }
        public function getFacilityId($location)
        {
            // Check if location exists, else return null
            if (!$location) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            $facility = Facility::where('name', $location)->first();
            return $facility ? $facility->id : null;
        }

        public function getCategoryId($category)
        {
            // Check if location exists, else return null
            if (!$category) {
                return null;
            }

            // Lookup the facility by location, or return null if not found
            $category = Category::where('description', $category)->first();
            return $category ? $category->id : null;
        }
}