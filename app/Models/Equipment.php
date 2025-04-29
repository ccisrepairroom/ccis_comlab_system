<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Rules\UniquePropertyCategoryEquipment;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



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
        'main_image',
        'alternate_images',
        'qr_code',
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
    public function liableUser()
    {
        return $this->belongsTo(User::class, 'person_liable');
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function equipmentMonitoring()
    {
        return $this->hasMany(EquipmentMonitoring::class);
    }

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

    protected $casts = [
        'alternate_images' => 'array'
    ];


}
