<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RencanaAktivitas;

class RencanaAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rencanaAktivitas = [
            ['nama_rencana' => 'DANA MASUK TABUNGAN (Rp)', 'is_active' => true],
            ['nama_rencana' => 'PICKUP SERVICE (RP)', 'is_active' => true],
            ['nama_rencana' => 'DANA MASUK GIRO (Rp)', 'is_active' => true],
            ['nama_rencana' => 'DANA MASUK DEPO (Rp)', 'is_active' => true],
            ['nama_rencana' => 'BRICUAN (Rp)', 'is_active' => true],
            ['nama_rencana' => 'EXTRA REWARD PRIO (Rp)', 'is_active' => true],
            ['nama_rencana' => 'BOOSTER DEPO (Rp)', 'is_active' => true],
            ['nama_rencana' => 'PANEN EXTRA TAB (Rp)', 'is_active' => true],
            ['nama_rencana' => 'BRING BACK FUND (Rp)', 'is_active' => true],
            ['nama_rencana' => 'CASA BRILINK (Rp)', 'is_active' => true],
            ['nama_rencana' => 'DISBURSEMENT KREDIT MIKRO (Rp)', 'is_active' => true],
            ['nama_rencana' => 'NASI KUNING (Rp)', 'is_active' => true],
            ['nama_rencana' => 'MERCY (Rp)', 'is_active' => true],
            ['nama_rencana' => 'SHL SMT II (Rp)', 'is_active' => true],
            ['nama_rencana' => 'AKURASI (Jumlah Rek)', 'is_active' => true],
            ['nama_rencana' => 'REK VQ (Jumlah Rek)', 'is_active' => true],
            ['nama_rencana' => 'REK H3 (Jumlah Rek)', 'is_active' => true],
            ['nama_rencana' => 'Akuisisi EDC/ QRIS (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'brifest spbu baraya (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'lucky ride (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Menyala Agenku (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Agen Ngebutz (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Akuisisi Incoming (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Akuisisi Hotspot (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Giro Pareto Belum EDC (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Giro Reward (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Suku Bunga Nego Giro (Jumlah)', 'is_active' => true],
            ['nama_rencana' => 'Cross Sell Perusahaan Anak', 'is_active' => true],
        ];

        foreach ($rencanaAktivitas as $data) {
            RencanaAktivitas::create($data);
        }
    }
}
