<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RMFT;
use Illuminate\Support\Facades\Hash;

class CreateAllRMFTUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all RMFT data
        $rmfts = RMFT::all();
        
        $this->command->info("Creating users for {$rmfts->count()} RMFT records...");
        
        $created = 0;
        $skipped = 0;
        
        foreach ($rmfts as $rmft) {
            // Check if user with this PERNR already exists
            $existingUser = User::where('pernr', $rmft->pernr)->first();
            
            if ($existingUser) {
                $skipped++;
                continue;
            }
            
            // Create user for this RMFT
            User::create([
                'name' => $rmft->completename,
                'email' => strtolower(str_replace(' ', '', $rmft->completename)) . '@rmft.bri',
                'password' => Hash::make('password'), // Default password: password
                'role' => 'rmft',
                'rmft_id' => $rmft->id,
                'pernr' => $rmft->pernr,
            ]);
            
            $created++;
        }
        
        $this->command->info("✓ Created {$created} new RMFT users");
        $this->command->info("✓ Skipped {$skipped} existing users");
        $this->command->info("");
        $this->command->info("All RMFT can now login using:");
        $this->command->info("- PERNR (e.g., {$rmfts->first()->pernr})");
        $this->command->info("- Password: password");
    }
}
