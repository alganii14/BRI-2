<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nasabah;
use App\Models\Uker;
use Illuminate\Support\Facades\DB;

class NasabahDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all ukers with their kanca data
        $ukers = Uker::whereNotNull('kode_sub_kanca')
                     ->whereNotNull('kode_kanca')
                     ->get();
        
        if ($ukers->isEmpty()) {
            $this->command->error('Tidak ada data uker. Import data uker terlebih dahulu.');
            return;
        }
        
        $segmenList = [
            'Ritel Badan Usaha',
            'SME',
            'Konsumer',
            'Prioritas',
            'Merchant',
            'Agen Brilink',
            'Mikro',
            'Komersial'
        ];
        
        $namaDepan = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gita', 'Hadi', 'Indah', 'Joko',
            'Kartika', 'Lina', 'Made', 'Nisa', 'Oki', 'Putri', 'Rudi', 'Sari', 'Tono', 'Uni',
            'Vina', 'Wawan', 'Yanti', 'Zahra', 'Agus', 'Bella', 'Candra', 'Dina', 'Edi', 'Farah',
            'Galih', 'Hana', 'Iwan', 'Julia', 'Kukuh', 'Lilis', 'Mega', 'Nanda', 'Oscar', 'Prita'
        ];
        
        $namaBelakang = [
            'Pratama', 'Wijaya', 'Santoso', 'Kusuma', 'Permana', 'Setiawan', 'Mahendra', 'Saputra',
            'Hidayat', 'Nugroho', 'Lestari', 'Purnama', 'Rahayu', 'Utama', 'Wibowo', 'Atmaja',
            'Kurniawan', 'Setiadi', 'Prabowo', 'Firmansyah', 'Hakim', 'Sutanto', 'Gunawan', 'Cahyadi',
            'Sasmita', 'Pradana', 'Sanjaya', 'Wahyudi', 'Putra', 'Indrianto', 'Mahardika', 'Suryanto'
        ];
        
        $this->command->info('Membuat 100 nasabah dummy...');
        
        for ($i = 1; $i <= 100; $i++) {
            // Random uker
            $uker = $ukers->random();
            
            // Generate norek (format: KC-Unit-RandomNumber)
            $norek = sprintf(
                '%s%s%08d',
                str_pad(substr($uker->kode_kanca, 0, 4), 4, '0', STR_PAD_LEFT),
                str_pad(substr($uker->kode_sub_kanca, 0, 3), 3, '0', STR_PAD_LEFT),
                rand(10000000, 99999999)
            );
            
            // Generate nama
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            
            // Random segmen
            $segmen = $segmenList[array_rand($segmenList)];
            
            Nasabah::create([
                'norek' => $norek,
                'nama_nasabah' => $nama,
                'segmen_nasabah' => $segmen,
                'kode_kc' => $uker->kode_kanca,
                'nama_kc' => $uker->kanca,
                'kode_uker' => $uker->kode_sub_kanca,
                'nama_uker' => $uker->sub_kanca,
            ]);
            
            if ($i % 10 == 0) {
                $this->command->info("Progress: {$i}/100 nasabah dibuat");
            }
        }
        
        $this->command->info('✓ Selesai! 100 nasabah dummy berhasil dibuat');
        $this->command->info('Nasabah tersebar di ' . $ukers->count() . ' unit kerja');
    }
}
