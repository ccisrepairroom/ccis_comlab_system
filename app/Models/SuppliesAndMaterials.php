<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliesAndMaterials extends Model
{
    use HasFactory;
    protected $fillable = [
        'item',
        'quantity',
        'stocking_point',
        'location',
        'stock_unit_id',
        'user_id',
        'created_at'
    ];


    public function stockUnit()
    {
        return $this->belongsTo(StockUnit::class, 'stock_unit_id');
    }

}
