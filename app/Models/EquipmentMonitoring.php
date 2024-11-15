<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Facility;



class EquipmentMonitoring extends Model
{
    use HasFactory;

    protected $table = 'equipment_monitorings';

    protected $fillable = [
        'equipment_id',
        'monitored_by',
        'monitored_date',
        'status',
        'facility_id',
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
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'monitored_by');
    }
}
