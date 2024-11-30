<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Filament\Resources\UserResource\Pages\Notification;


class UserImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row){
        $userId = auth()->id(); 
        // Trim and retrieve related models
        $roleName = trim($row['role_id'] ?? '');
    
        $role = $roleName ? Role::firstOrCreate(['name' => $roleName], ['name' => $roleName]) : null;
    
    // Prepare data array with null checks
$data = [
    'name' => $row['name'] ?? null,
    'email' => $row['email'] ?? null,
    'role' =>  $this->getRoleId($row['role']) ?? null,
     //$row['role'] ?? null,
    'password' => $row['password'] ?? null,
    'department' => $row['department'] ?? null,
    'designation' => $row['designation'] ?? null,
    'created_at' => $row['created_at'] ?? null,
    
];
 
    // Define essential fields to check
    $essentialFields = [
        'name',
        'email',
        'role' ,
        'department',
        'designation',
        'password',
        'created_at',
      


       ];
       // Extract only the essential fields
       $filteredData = array_intersect_key($data, array_flip($essentialFields));

       // Check if any of the essential fields have meaningful data
       if (!array_filter($filteredData, fn($value) => !is_null($value) && $value !== '')) {
           // If the row is blank, return null to skip insertion
           return null;
       }

       // Create and return new Equipment instance if the row has data
       return new User($data);
       }
       public function getRoleId($role)
    {
        // Check if location exists, else return null
        if (!$role) {
            return null;
        }

       

        $role = Role::firstOrCreate(['name' => $role], ['name' => $role]);
        return $role->id;
    }
    



}
