<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RMFT;
use App\Models\Uker;
use Illuminate\Support\Facades\Hash;

class UpdateUsersWithKancaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Updating RMFT users with Kanca data...");
        
        // Update all RMFT users with their Kanca
        $rmftUsers = User::where('role', 'rmft')->whereNotNull('rmft_id')->get();
        
        foreach ($rmftUsers as $user) {
            if ($user->rmftData) {
                $user->update([
                    'kode_kanca' => $user->rmftData->ukerRelation->kode_kanca ?? null,
                    'nama_kanca' => $user->rmftData->kanca ?? null,
                ]);
            }
        }
        
        $this->command->info("✓ Updated {$rmftUsers->count()} RMFT users with Kanca data");
        
        // Create Manager accounts per Kanca
        $this->command->info("\nCreating Manager accounts per Kanca...");
        
        $kancaList = RMFT::select('kanca')
                        ->distinct()
                        ->whereNotNull('kanca')
                        ->get();
        
        $created = 0;
        
        foreach ($kancaList as $kancaData) {
            $kanca = $kancaData->kanca;
            
            // Get Uker data for this Kanca
            $uker = Uker::where('kanca', $kanca)->first();
            
            if (!$uker) continue;
            
            // Check if manager already exists
            $existingManager = User::where('role', 'manager')
                                   ->where('nama_kanca', $kanca)
                                   ->first();
            
            if ($existingManager) continue;
            
            // Create manager email
            $email = 'manager.' . strtolower(str_replace(' ', '', $kanca)) . '@bri.co.id';
            
            User::create([
                'name' => 'Manager ' . $kanca,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'manager',
                'kode_kanca' => $uker->kode_kanca,
                'nama_kanca' => $kanca,
            ]);
            
            $created++;
        }
        
        $this->command->info("✓ Created {$created} Manager accounts");
        $this->command->info("\nManager accounts created with:");
        $this->command->info("- Email format: manager.namakanca@bri.co.id");
        $this->command->info("- Password: password");
    }
}
