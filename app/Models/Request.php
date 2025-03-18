<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use App\Models\OrderItem;
use App\Models\FrequentShopper;
use App\Models\Request;
use App\Models\User;





class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_request',
        'request_status',
        'notes',
        'timestamps',
       
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function items(){
        return $this->hasMany(RequestItem::class);
    }
    public function requestInfo(){
        return $this->hasOne(RequestInfo::class);
    }

}