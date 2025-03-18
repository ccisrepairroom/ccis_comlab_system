<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;
    protected $fillable=[
        'request_id',
        'equipment_id',
        'facility_id',
        'supplies_and_materials_id',
        'quantity',
    ];

    public function request(){
        return $this->belongsTo(Request::class);
    }
    public function equipment(){
        return $this->belongsTo(Equipment::class);
    }
    public function facility(){
        return $this->belongsTo(Facility::class);
    }
    public function supplies_and_materials(){
        return $this->belongsTo(SuppliesAndMaterials::class);
    }
    public function items()
    {
        return $this->hasMany(RequestItem::class);
    }
}
