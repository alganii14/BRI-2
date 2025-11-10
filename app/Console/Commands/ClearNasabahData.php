<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearNasabahData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nasabah:clear {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus semua data nasabah dari database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $totalRecords = DB::table('nasabahs')->count();
            
            $this->line("");
            $this->warn("⚠️  PERINGATAN: Anda akan menghapus SEMUA data nasabah!");
            $this->info("📊 Total data yang akan dihapus: " . number_format($totalRecords, 0, ',', '.') . " records");
            $this->line("");

            if (!$this->option('force')) {
                if (!$this->confirm('Apakah Anda yakin ingin melanjutkan?', false)) {
                    $this->info('❌ Penghapusan dibatalkan.');
                    return 0;
                }

                // Double confirmation for safety
                $this->line("");
                $this->error('🚨 KONFIRMASI TERAKHIR! Data yang dihapus TIDAK DAPAT dikembalikan!');
                if (!$this->confirm('Yakin 100% ingin menghapus?', false)) {
                    $this->info('❌ Penghapusan dibatalkan.');
                    return 0;
                }
            }

            $this->line("");
            $this->info("🗑️  Menghapus data nasabah...");
            $startTime = microtime(true);

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Truncate table (fastest method - reset auto increment)
            DB::table('nasabahs')->truncate();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $this->line("");
            $this->info("✅ Berhasil menghapus {$totalRecords} data nasabah!");
            $this->info("⏱️  Waktu proses: {$duration} detik");
            $this->line("");

            return 0;

        } catch (\Exception $e) {
            // Make sure to re-enable foreign key checks on error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->line("");
            $this->error("❌ Error: " . $e->getMessage());
            $this->line("");
            
            return 1;
        }
    }
}
