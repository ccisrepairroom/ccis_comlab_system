<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipment_id',
        'facility_id',
        'date',
        'purpose',
        'date_and_time_of_use',
        'college_department_office',
        'request_form',
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
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }
}
