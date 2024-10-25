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
        'stock_unit_id',
        'facility_id',
        'user_id',
        'created_at'
    ];


    public function stockUnit()
{
    return $this->belongsTo(StockUnit::class, 'stock_unit_id');
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function supplies_cart()
    {
        return $this->belongsTo(SuppliesCart::class);
    }


}
