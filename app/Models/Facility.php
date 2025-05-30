<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'connection_type',
        'facility_type',
        'cooling_tools',
        'floor_level',
        'building',
        'remarks',
        'main_image',
        'alternate_images',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function equipment()
{
    return $this->hasMany(Equipment::class, 'facility_id');
}

public function monitorings()
{
    return $this->hasMany(FacilityMonitoring::class);
}

protected $casts = [
    'alternate_images' => 'array'
];

}
