<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use app\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=> 'SuperAdmin',
            'email'=> 'superadmin@ccis.edu.ph',
            'password'=> '$2y$12$CrKAI857UyQ/L1Bd01JyjekfUqpoxuB2oJ64UsCFaCeysr95Is6mq',
            //'remember_token'=> Str::random(10),
        ])->assignRole('super_admin');
    }
}
//pass is ccis capstone members
