<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Manager user
        User::create([
            'name' => 'Manager',
            'email' => 'manager@admin.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create RMFT user
        User::create([
            'name' => 'RMFT User',
            'email' => 'rmft@admin.com',
            'password' => Hash::make('password'),
            'role' => 'rmft',
        ]);
        
        // Create default admin user (manager)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
    }
}
