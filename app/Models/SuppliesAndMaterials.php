<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;



class SuppliesAndMaterials extends Model
{
    use HasFactory;
    protected $fillable = [
        'item',
        'quantity',
        'category_id',
        'stocking_point',
        'stock_unit_id',
        'facility_id',
        'category_id',
        'user_id',
        'supplier',
        'item_img',
        'remarks',
        'created_at'
    ];


    public function stockUnit()
    {
        return $this->belongsTo(StockUnit::class, 'stock_unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function supplies_cart()
    {
        return $this->belongsTo(SuppliesCart::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getDateAcquiredAttribute($value)
    {
        // Check if the value is numeric (Excel-style date)
        if (is_numeric($value)) {
            // Convert the numeric date (Excel date) to a proper date format
            $excelStartDate = Carbon::createFromFormat('Y-m-d', '1900-01-01');
            $date = $excelStartDate->addDays($value - 2); // Adjust for Excel's leap year bug
            return $date->timezone('Asia/Manila')->format('M-d-y');
        }

        // If it's not numeric, parse it as a normal date
        return Carbon::parse($value)->timezone('Asia/Manila')->format('M-d-y');
    }
    public function stockMonitoring()
    {
        return $this->hasMany(StockMonitoring::class, 'stock_monitoring_id');
    }




}
