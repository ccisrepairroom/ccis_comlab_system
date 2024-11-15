<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplies_and_materials_id',
        'facility_id',
        'monitored_by',
        'current_quantity',
        'quantity_to_add',
        'new_quantity',
        'supplier',
        'monitored_date',
    ];

    /**
     * Relationships
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'monitored_by');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function stockUnit()
    {
        return $this->belongsTo(StockUnit::class, 'stock_unit_id');
    }
   
    public function suppliesAndMaterials()
    {
        return $this->belongsTo(SuppliesAndMaterials::class,'supplies_and_materials_id');
    }

}
