<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentMonitoring; // Ensure you're using the correct model

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Get the filters from the request
        $monitoringDate = $request->input('monitoring_date');

        // Query the monitoring records
        $monitorings = EquipmentMonitoring::with(['user', 'facility']) // Eager load relationships
            ->when($monitoringDate, function ($query) use ($monitoringDate) {
                $query->whereDate('monitored_date', $monitoringDate);
            })
            ->get();

        // Return the view with the filtered results
        return view('monitorings.index', compact('monitorings'));
    }
}
