<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EightyNine\Approvals\Models\ApprovableModel;



class BorrowedItems extends ApprovableModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrowed_by',
        'equipment_id',
        'facility_id',
        'request_status',
        'request_form',
        'returned_date',
        'borrowed_date',
        'status',
        'remarks',
        'date',
        'purpose',
        'start_date_and_time_of_use',
        'end_date_and_time_of_use',
        'expected_return_date',
        'received_by',
        'college_department_office'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function borrowList()
    {
        return $this->belongsTo(BorrowList::class);
    }
}
