<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUnit extends Model
{
    use HasFactory;

    protected $fillable = ['description'];
    public function user()
{
    return $this->belongsTo(User::class);
}
public function suppliesAndMaterials()
{
    return $this->hasMany(SuppliesAndMaterials::class, 'stock_unit_id');
}
}
