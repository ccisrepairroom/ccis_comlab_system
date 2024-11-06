<?php

namespace App\Imports;

use App\Models\Facility;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacilityImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the row has any meaningful data before processing
        if (empty(array_filter($row, fn($value) => !is_null($value) && $value !== ''))) {
            // Skip insertion if the row is entirely empty
            return null;
        }

        // Check if a facility with the same name already exists, if name is provided
        if (!empty($row['name'])) {
            $existingFacility = Facility::where('name', $row['name'])->first();
            if ($existingFacility) {
                // Skip insertion if a facility with the same name already exists
                return null;
            }
        }

        // Map available columns to the Facility model
        $data = [
            'name' => $row['name'] ?? null,
            'connection_type' => $row['connection_type'] ?? null,
            'facility_type' => $row['facility_type'] ?? null,
            'cooling_tools' => $row['cooling_tools'] ?? null,
            'floor_level' => $row['floor_level'] ?? null,
            'building' => $row['building'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'user_id' => $row['user_id'] ?? null,
        ];

        // Filter out any keys where the value is null, keeping only provided data
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');

        // If there's at least one non-null column, create a new Facility instance
        return !empty($data) ? new Facility($data) : null;
    }
}
