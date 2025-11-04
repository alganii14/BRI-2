<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin already exists
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            
            $this->command->info('✓ Admin user created successfully!');
            $this->command->info('Email: admin@admin.com');
            $this->command->info('Password: password');
        } else {
            // Update existing admin to ensure role is 'admin'
            $admin->update(['role' => 'admin']);
            $this->command->info('✓ Admin user already exists and role updated!');
        }
    }
}
