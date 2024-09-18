<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Admin extends Seeder
{
    public function run(): void
    {
        // Insert Admin user with role_id 1 (Admin)
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // You can use any password
            'role_id' => 1, // Reference role_id 1 for Admin
        ]);

        // Insert Subscriber user with role_id 2 (Subscriber)
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Subscriber User',
            'email' => 'subscriber@example.com',
            'password' => Hash::make('password'), // You can use any password
            'role_id' => 2, // Reference role_id 2 for Subscriber
        ]);
    }
}
