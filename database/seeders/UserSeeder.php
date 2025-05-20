<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  // <-- Import Hash facade
use Spatie\Permission\Models\Role;
use App\Models\User;  // Fix namespace (App not app)

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Main Developer',
            'email' => 'maindeveloper@gmail.com',
            'department' => 'Computer Science',
            'designation' => 'Comlab Adviser',
            'role_id' => 1,
            'password' => Hash::make('maindeveloper'),  // <-- Hash the password here
            //'remember_token' => Str::random(10),
        ])->assignRole('super_admin');
    }
}
