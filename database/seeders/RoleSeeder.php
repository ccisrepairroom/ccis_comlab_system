<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'super_admin',
            'guard_name' => 'web',
            
        ]);
        Role::create([
            'id' => 2,
            'name' => 'admin',
            'guard_name' => 'web',
            
        ]);
        Role::create([
            'id' => 3,
            'name' => 'staff',
            'guard_name' => 'web',
            
        ]);
        Role::create([
            'id' => 4,
            'name' => 'faculty',
            'guard_name' => 'web',
            
        ]);
        Role::create([
            'id' => 5,
            'name' => 'other',
            'guard_name' => 'web',
            
        ]);
    }
}