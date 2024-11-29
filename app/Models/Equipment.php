<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Rules\UniquePropertyCategoryEquipment;
use Illuminate\Support\Facades\Validator;


class Equipment extends Model
{
    use HasFactory;

    // const ITEM_PREFIX = 'ITEM';
    // const ITEM_COLUMN = 'item_number';
    // const PROPERTY_PREFIX = 'PROP';
    // const PROPERTY_COLUMN = 'property_number';
    // const CONTROL_PREFIX = 'CTRL';
    // const CONTROL_COLUMN = 'control_number';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    protected $fillable = [
        'unit_no',
        'brand_name',
        'description',
        'facility_id',
        'category_id',
        'user_id',
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
        'person_liable',
        'remarks',
        //'stock_unit_id',
        'name',
        'availability'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function equipmentMonitoring()
    {
        return $this->hasMany(EquipmentMonitoring::class);
    }

    /*public function stockUnit()
    {
        return $this->belongsTo(StockUnit::class, 'stock_unit_id');
    }
*/
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Manila')->format('F d, Y h:i A');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Manila')->format('F d, Y h:i A');
    }
    public function borrowedItems()
    {
        return $this->hasMany(BorrowedItems::class);
    }
    /*public function getDateAcquiredAttribute($value)
    {
        // Check if the value is numeric (Excel-style date)
        if (is_numeric($value)) {
            // Convert the numeric date (Excel date) to a proper date format
            $excelStartDate = Carbon::createFromFormat('Y-m-d', '1900-01-01');
            $date = $excelStartDate->addDays($value - 2); // Adjust for Excel's leap year bug
            return $date->timezone('Asia/Manila')->format('M-d-y');
        }

        // If it's not numeric, parse it as a normal date
        return Carbon::parse($value)->timezone('Asia/Manila')->format('M-d-y');
    }*/

    /*protected static function booted()
    {
        static::creating(function ($equipment) {
            // Validate using the custom rule before saving the record
            $validator = Validator::make($equipment->attributesToArray(), [
                'property_no' => [
                    'required',
                    new UniquePropertyCategoryEquipment($equipment->category_id),
                ],
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        });
    }*/

}
