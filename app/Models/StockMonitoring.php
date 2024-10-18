<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'facility_id',
        'monitored_by',
        'available_stock',
        'action_type',
        'stock_quantity',
        'new_stock',
        'created_at',
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
}
