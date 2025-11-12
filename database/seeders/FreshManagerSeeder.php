<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RMFT;
use App\Models\Uker;
use Illuminate\Support\Facades\Hash;

class FreshManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("🔄 Refreshing Manager accounts...");
        
        // Delete all existing manager accounts
        $deletedCount = User::where('role', 'manager')->delete();
        $this->command->info("🗑️  Deleted {$deletedCount} existing Manager accounts");
        
        // Create fresh Manager accounts per Kanca
        $this->command->info("\n📝 Creating fresh Manager accounts per Kanca...");
        
        $kancaList = RMFT::select('kanca')
                        ->distinct()
                        ->whereNotNull('kanca')
                        ->orderBy('kanca', 'asc')
                        ->get();
        
        $created = 0;
        
        foreach ($kancaList as $kancaData) {
            $kanca = $kancaData->kanca;
            
            // Get Uker data for this Kanca - try exact match first
            $uker = Uker::where('kanca', $kanca)->first();
            
            // If not found, try similar match (untuk kasus seperti Soekarno vs Sukarno)
            if (!$uker) {
                // Coba variasi ejaan umum
                $variations = [
                    str_replace('Soekarno', 'Sukarno', $kanca),
                    str_replace('Soeharto', 'Suharto', $kanca),
                ];
                
                foreach ($variations as $variation) {
                    $uker = Uker::where('kanca', $variation)->first();
                    if ($uker) {
                        $this->command->info("📝 Found variant: '{$variation}' for '{$kanca}'");
                        break;
                    }
                }
            }
            
            if (!$uker) {
                $this->command->warn("⚠️  Skipping {$kanca} - no Uker data found");
                continue;
            }
            
            // Create manager email (clean format)
            $cleanKanca = strtolower(str_replace([' ', '.'], '', $kanca));
            $email = 'manager.' . $cleanKanca . '@bri.co.id';
            
            User::create([
                'name' => 'Manager ' . $kanca,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'manager',
                'kode_kanca' => $uker->kode_kanca,
                'nama_kanca' => $kanca,
            ]);
            
            $this->command->info("✓ Created: {$email} - {$kanca}");
            $created++;
        }
        
        $this->command->info("\n✅ Successfully created {$created} fresh Manager accounts");
        $this->command->info("\n📋 Login Credentials:");
        $this->command->info("   Email format: manager.[kanca]@bri.co.id");
        $this->command->info("   Password: password");
        $this->command->info("\nExample:");
        $this->command->info("   Email: manager.kcbandungdago@bri.co.id");
        $this->command->info("   Password: password");
    }
}
