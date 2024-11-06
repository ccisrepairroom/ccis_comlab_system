<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

public function user()
{
    return $this->belongsTo(User::class);
}
public function equipment()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}
