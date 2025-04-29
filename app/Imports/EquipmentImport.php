<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\Category;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Filament\Notifications\Notification;
use Exception;

class EquipmentImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected $missingUsers = [];

    public function model(array $row)
    {
        // Skip rows where brand_name is missing
        if (empty($row['brand_name'])) {
            return null;
        }

        $serialNumber = $row['serial_number'] ?? null;
        $status = strtolower(trim($row['status'] ?? ''));

        // Check if an equipment with the same serial number exists
        $existingEquipment = Equipment::where('serial_no', $serialNumber)->first();

        if ($existingEquipment && !in_array(strtolower($serialNumber), ['n/a'])) {
            // If the status is the same, show notification and skip
            if (strtolower($existingEquipment->status) === $status) {
                Notification::make()
                    ->title('Duplicate Serial Number')
                    ->body("Equipment with Serial Number: {$serialNumber} and Status: {$status} already exists.")
                    ->danger()
                    ->duration(5000)
                    ->send();

                return null; // Skip inserting this duplicate record
            }

            // If status in Excel is 'for disposal' or 'disposed', update existing record
            if (in_array($status, ['for disposal', 'disposed'])) {
                $existingEquipment->update(['status' => $status]);
                return null; // Skip inserting a duplicate entry
            }
        }

    // Check if person liable exists, if not create a user
    $userName = trim($row['person_liable'] ?? '');

    if ($userName) {
        $existingUser = User::where('name', $userName)->first();

        if (!$existingUser) {
            // Create a new user with default attributes
            $newUser = User::create([
                'name' => $userName,
                'email' => strtolower(str_replace(' ', '', $userName)) . '@carsu.edu.ph',
                'role_id' => 5,
                'designation' => 'other',
                'department' => 'not_applicable',
                'password' => bcrypt('manana'),
            ]);
        }
    }

    $facilityName = trim($row['facility'] ?? '');
    $categoryDescription = trim($row['category'] ?? '');

    $facility = $facilityName ? Facility::firstOrCreate(['name' => $facilityName], ['name' => $facilityName]) : null;
    $category = $categoryDescription ? Category::firstOrCreate(['description' => $categoryDescription], ['description' => $categoryDescription]) : null;

    $data = [
        'unit_no' => $row['unit_number'] ?? null,
        'brand_name' => $row['brand_name'] ?? null,
        'description' => $row['description'] ?? null,
        'user_id' => User::where('name', $userName)->value('id') ?? null,
        'facility_id' => $facility ? $facility->id : null,
        'category_id' => $category ? $category->id : null,
        'status' => $status,
        'date_acquired' => $row['date_acquired'] ?? null,
        'supplier' => $row['supplier'] ?? null,
        'amount' => $row['amount'] ?? null,
        'estimated_life' => $row['estimated_life'] ?? null,
        'item_no' => $row['item_number'] ?? null,
        'po_number' => $row['po_number'] ?? null,
        'property_no' => $row['property_number'] ?? null,
        'control_no' => $row['control_number'] ?? null,
        'serial_no' => $serialNumber,
        'remarks' => $row['remarks'] ?? null,
    ];

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
