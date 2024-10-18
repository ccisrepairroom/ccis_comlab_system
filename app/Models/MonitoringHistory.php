<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringHistory extends Model
{
    use HasFactory;
    protected $table = 'monitoring_history';
    
    protected $fillable = [
        'user_id',
        'equipment_id',
        'facility_id',
        'category_id',
        'stock_unit_id',
        'actions_taken',
        'actions_taken_date',
        'remarks',
    ];
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
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function stockUnit()
    {
        return $this->belongsTo(StockUnit::class);
    }
}
