<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UniquePropertyCategoryEquipment implements Rule
{
    protected $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function passes($attribute, $value)
    {
        // Log the values being validated
        Log::info("Validating Property Number: $value with Category ID: {$this->categoryId}");

        // Check if the combination of property_no and category_id already exists in the equipment table
        return !DB::table('equipment')
            ->where('property_no', $value)
            ->where('category_id', $this->categoryId)
            ->exists();
    }

    public function message()
    {
        return 'This property number with the same category already exists.';
    }
}
