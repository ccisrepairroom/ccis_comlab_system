<?php

namespace App\Imports;

use App\Models\Facility;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class FacilityImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $facilityName = trim($row['name'] ?? '');

        // Check if the category with the description already exists
        $existingFacility = Facility::where('name', $facilityName)->first();

        // If the category already exists, skip insertion
        if ($existingFacility) {
            return null;
        }
        // Prepare data array with null checks
        $data = [
            'name' => $row['name'] ?? null,
            'connection_type' => $row['connection_type'] ?? null,
            'facility_type' => $row['facility_type'] ?? null,
            'cooling_tools' => $row['cooling_tools'] ?? null,
            'floor_level' => $row['floor_level'] ?? null,
            'building' => $row['building'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'user_id' => $userId ?? null, 

        ];

        // Check if the row has any meaningful data before processing
        if (!array_filter($data, fn($value) => !is_null($value) && $value !== '')) {
            // If the row is blank, return null to skip insertion
            return null;
        }

        // Check if a facility with the same name already exists
        $existingFacility = Facility::where('name', $data['name'])->first();

        if ($existingFacility) {
            // If a facility with the same name already exists, skip insertion
            return null;
        }

        // If no duplicate is found, create a new Facility instance
        return new Facility($data);
    }
}
