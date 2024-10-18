<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\User;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class CategoryImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $categoryDescription = trim($row['description'] ?? '');

        // Check if the category with the description already exists
        $existingCategory = Category::where('description', $categoryDescription)->first();

        // If the category already exists, skip insertion
        if ($existingCategory) {
            return null;
        }

        // Prepare data array with null checks
        $data = [
            'description' => $categoryDescription,
            'user_id' => $userId ?? null, 

        ];

        // Create and return new Category instance if the description does not already exist
        return new Category($data);
    }
}
