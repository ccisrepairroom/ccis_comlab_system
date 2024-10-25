<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliesCart extends Model
{
    use HasFactory;
    protected $table = 'supplies_cart';
    protected $fillable = [
        'user_id',
        'facility_id',
        'supplies_and_materials_id',
        'available_quantity',
        'quantity_requested',
        'action_date',
        
    ];
    public function supplies_and_materials()
    {
        return $this->belongsTo(SuppliesAndMaterials::class);
    }
    
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
