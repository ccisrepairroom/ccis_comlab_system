<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
    use HasFactory;
    protected $fillable=[
        'request_id',
        'name',
        'phone_number',
        'college_department',
        'purpose',
        'notes',
        'start_date_and_time_of_use',
        'end_date_and_time_of_use',

    ];
    public function request(){
        return $this->belongsTo(Request::class);
    }
   
}
