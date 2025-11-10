<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Nasabah;

class ImportNasabahCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:nasabah {file : Path ke file CSV}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data nasabah dari file CSV (support file besar 800MB+)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Set unlimited memory and time for large files
        ini_set('memory_limit', '2048M'); // 2GB
        set_time_limit(0); // Unlimited execution time
        
        $filePath = $this->argument('file');

        // Validasi file exists
        if (!file_exists($filePath)) {
            $this->error("❌ File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Validasi file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, ['csv', 'txt'])) {
            $this->error("❌ File harus berformat CSV atau TXT");
            return 1;
        }

        $fileSize = filesize($filePath);
        $fileSizeMB = round($fileSize / (1024 * 1024), 2);
        
        $this->info("📁 File: " . basename($filePath));
        $this->info("📊 Ukuran: {$fileSizeMB} MB");
        $this->line("");

        if (!$this->confirm('Lanjutkan import?', true)) {
            $this->info('Import dibatalkan.');
            return 0;
        }

        $startTime = microtime(true);
        $this->info("⏳ Memulai import...");
        $this->line("");

        try {
            $handle = fopen($filePath, 'r');
            
            if ($handle === false) {
                $this->error("❌ Tidak dapat membuka file");
                return 1;
            }

            // Skip header row
            $header = fgetcsv($handle);
            
            $batch = [];
            $batchSize = 500; // Reduced for large files to save memory
            $totalInserted = 0;
            $skipped = 0;
            $rowNumber = 1; // Start from 1 (after header)
            
            // Create progress bar
            $this->output->write("📈 Progress: ");
            $progressStart = true;

            DB::beginTransaction();
            DB::connection()->disableQueryLog();
            
            // Disable Eloquent event listeners for performance
            \App\Models\Nasabah::unsetEventDispatcher();

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Skip empty rows
                if (count($row) < 10 || empty(trim($row[8]))) {
                    continue;
                }

                // Parse kode_kc and kode_uker
                $kode_kc = null;
                $nama_kc = null;
                if (!empty($row[3])) {
                    preg_match('/^(\d+)\s*--\s*(.+)$/', trim($row[3]), $matches);
                    if (isset($matches[1]) && isset($matches[2])) {
                        $kode_kc = ltrim($matches[1], '0') ?: '0';
                        $nama_kc = trim($matches[2]);
                    }
                }

                $kode_uker = null;
                $nama_uker = null;
                if (!empty($row[4])) {
                    preg_match('/^(\d+)\s*--\s*(.+)$/', trim($row[4]), $matches);
                    if (isset($matches[1]) && isset($matches[2])) {
                        $kode_uker = ltrim($matches[1], '0') ?: '0';
                        $nama_uker = trim($matches[2]);
                    }
                }

                // Parse dates
                $tanggal_buka = null;
                if (!empty(trim($row[9]))) {
                    try {
                        $tanggal_buka = date('Y-m-d', strtotime(trim($row[9])));
                        if ($tanggal_buka === '1970-01-01') {
                            $tanggal_buka = null;
                        }
                    } catch (\Exception $e) {
                        $tanggal_buka = null;
                    }
                }

                $tanggal_jt = null;
                if (!empty(trim($row[10]))) {
                    try {
                        $tanggal_jt = date('Y-m-d', strtotime(trim($row[10])));
                        if ($tanggal_jt === '1970-01-01') {
                            $tanggal_jt = null;
                        }
                    } catch (\Exception $e) {
                        $tanggal_jt = null;
                    }
                }

                // Helper function to parse decimal
                $parseDecimal = function($value) {
                    if (empty($value)) return null;
                    $cleaned = str_replace(',', '', trim($value));
                    return is_numeric($cleaned) ? (float) $cleaned : null;
                };

                $batch[] = [
                    'norek' => trim($row[8]),
                    'cifno' => !empty(trim($row[6])) ? trim($row[6]) : null,
                    'nama_nasabah' => !empty(trim($row[7])) ? trim($row[7]) : null,
                    'kode_kc' => $kode_kc,
                    'nama_kc' => $nama_kc,
                    'kode_uker' => $kode_uker,
                    'nama_uker' => $nama_uker,
                    'regional_office' => !empty(trim($row[2])) ? trim($row[2]) : null,
                    'kantor_cabang' => !empty(trim($row[3])) ? trim($row[3]) : null,
                    'unit_kerja' => !empty(trim($row[4])) ? trim($row[4]) : null,
                    'jenis_uker' => !empty(trim($row[5])) ? trim($row[5]) : null,
                    'tanggal_pembukaan_rekening' => $tanggal_buka,
                    'tanggal_jatuh_tempo' => $tanggal_jt,
                    'status' => isset($row[11]) && !empty(trim($row[11])) ? trim($row[11]) : null,
                    'segmentasi_bisnis' => isset($row[12]) && !empty(trim($row[12])) ? trim($row[12]) : null,
                    'segmentasi_divisi' => isset($row[13]) && !empty(trim($row[13])) ? trim($row[13]) : null,
                    'segmen_nasabah' => isset($row[12]) && !empty(trim($row[12])) ? trim($row[12]) : 'KONSUMER',
                    'tipe_produk' => isset($row[14]) && !empty(trim($row[14])) ? trim($row[14]) : null,
                    'jenis_simpanan' => isset($row[15]) && !empty(trim($row[15])) ? trim($row[15]) : null,
                    'jenis_kartu' => isset($row[16]) && !empty(trim($row[16])) ? trim($row[16]) : null,
                    'kelompok_produk' => isset($row[17]) && !empty(trim($row[17])) ? trim($row[17]) : null,
                    'deskripsi_produk' => isset($row[18]) && !empty(trim($row[18])) ? trim($row[18]) : null,
                    'jangka_waktu' => isset($row[19]) && !empty(trim($row[19])) ? trim($row[19]) : null,
                    'jenis_valuta' => isset($row[20]) && !empty(trim($row[20])) ? trim($row[20]) : null,
                    'saldo_original' => isset($row[21]) ? $parseDecimal($row[21]) : null,
                    'saldo_idr' => isset($row[22]) ? $parseDecimal($row[22]) : null,
                    'ratas_saldo_marginal' => isset($row[23]) ? $parseDecimal($row[23]) : null,
                    'ratas_saldo_historical' => isset($row[24]) ? $parseDecimal($row[24]) : null,
                    'beban_bunga' => isset($row[25]) ? $parseDecimal($row[25]) : null,
                    'rate_simpanan' => isset($row[26]) ? $parseDecimal($row[26]) : null,
                    'pn_customer_service' => isset($row[27]) && !empty(trim($row[27])) ? trim($row[27]) : null,
                    'pn_mantri_rm_dana' => isset($row[28]) && !empty(trim($row[28])) ? trim($row[28]) : null,
                    'pn_rm_pinjaman' => isset($row[29]) && !empty(trim($row[29])) ? trim($row[29]) : null,
                    'pn_rm_merchant' => isset($row[30]) && !empty(trim($row[30])) ? trim($row[30]) : null,
                    'pn_relationship_officer' => isset($row[31]) && !empty(trim($row[31])) ? trim($row[31]) : null,
                    'pn_sales_person' => isset($row[32]) && !empty(trim($row[32])) ? trim($row[32]) : null,
                    'pn_rab' => isset($row[33]) && !empty(trim($row[33])) ? trim($row[33]) : null,
                    'pn_rm_referral' => isset($row[34]) && !empty(trim($row[34])) ? trim($row[34]) : null,
                    'kepemilikan_brimo' => isset($row[35]) && !empty(trim($row[35])) ? trim($row[35]) : null,
                    'referral' => isset($row[36]) && !empty(trim($row[36])) ? trim($row[36]) : null,
                    'status_pekerja' => isset($row[37]) && !empty(trim($row[37])) ? trim($row[37]) : null,
                    'status_cif' => isset($row[38]) && !empty(trim($row[38])) ? trim($row[38]) : null,
                    'referral_code' => isset($row[39]) && !empty(trim($row[39])) ? trim($row[39]) : null,
                    'flag_pn_pengelola_slot_2' => isset($row[40]) && !empty(trim($row[40])) ? trim($row[40]) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    try {
                        DB::table('nasabahs')->insert($batch);
                        $totalInserted += count($batch);
                        
                        // Show progress
                        if ($totalInserted % 5000 == 0) {
                            $this->output->write(".");
                        }
                        
                    } catch (\Exception $e) {
                        // Skip duplicates
                        $skipped += count($batch);
                    }
                    
                    // Clear batch and free memory
                    $batch = [];
                    
                    // Aggressive garbage collection for large files
                    if ($totalInserted % 5000 == 0) {
                        gc_collect_cycles();
                        
                        // Periodic commit to avoid long transactions
                        if ($totalInserted % 50000 == 0) {
                            DB::commit();
                            DB::beginTransaction();
                        }
                    }
                }
            }

            // Insert remaining batch
            if (!empty($batch)) {
                try {
                    DB::table('nasabahs')->insert($batch);
                    $totalInserted += count($batch);
                } catch (\Exception $e) {
                    $skipped += count($batch);
                }
            }

            fclose($handle);
            DB::commit();

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;

            $this->line("");
            $this->line("");
            $this->info("✅ Import selesai!");
            $this->line("");
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Total Baris Diproses', number_format($rowNumber - 1, 0, ',', '.')],
                    ['Data Berhasil Diimport', number_format($totalInserted, 0, ',', '.')],
                    ['Data Dilewati (Duplikat/Error)', number_format($skipped, 0, ',', '.')],
                    ['Waktu Proses', sprintf('%d menit %d detik', $minutes, $seconds)],
                    ['Kecepatan', number_format($totalInserted / $duration, 0) . ' records/detik'],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            
            $this->error("❌ Error: " . $e->getMessage());
            $this->line("Stack trace:");
            $this->line($e->getTraceAsString());
            
            return 1;
        }
    }
}
