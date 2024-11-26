<?php

namespace App\Imports;

use App\Models\StockUnit;
use App\Models\User;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class StockUnitImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $stockunitDescription = trim($row['description'] ?? '');

        // Check if the category with the description already exists
        $existingStockUnit = StockUnit::where('description', $stockunitDescription)->first();

        // If the category already exists, skip insertion
        if ($existingStockUnit) {
            return null;
        }

        // Prepare data array with null checks
        $data = [
            'description' => $stockunitDescription,
            'user_id' => $userId ?? null, 

        ];

        // Create and return new Category instance if the description does not already exist
        return new StockUnit($data);
    }
}
