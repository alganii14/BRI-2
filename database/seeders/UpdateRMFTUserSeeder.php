<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RMFT;

class UpdateRMFTUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get first RMFT data
        $rmft = RMFT::first();
        
        if ($rmft) {
            // Update RMFT user
            $user = User::where('email', 'rmft@admin.com')->first();
            if ($user) {
                $user->update([
                    'rmft_id' => $rmft->id,
                    'pernr' => $rmft->pernr,
                    'name' => $rmft->completename,
                ]);
                
                $this->command->info("User '{$user->email}' updated with RMFT data:");
                $this->command->info("- Name: {$rmft->completename}");
                $this->command->info("- PERNR: {$rmft->pernr}");
                $this->command->info("- Can now login with PERNR: {$rmft->pernr}");
            }
        }
    }
}
