<?php

namespace App\Exports;

use App\Models\Borrow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Fetch the data you want to export
        return Borrow::all(); // You can customize this to return only the necessary data
    }

    public function headings(): array
    {
        // Define the headings for the export file
        return [
            'ID',
            'User ID',
            'Equipment ID',
            'Facility ID',
            'Request Status',
            'Request Form',
            'Borrowed Date',
            'Returned Date',
            // Add more headings as needed
        ];
    }
}
