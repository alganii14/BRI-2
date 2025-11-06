<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenurunanNoSegmentRitel;
use League\Csv\Reader;

class PenurunanNoSegmentRitelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvPath = base_path('PENURUNAN NO-SEGMENT RITEL.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("File tidak ditemukan: {$csvPath}");
            return;
        }

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);
        
        $records = $csv->getRecords();
        
        foreach ($records as $record) {
            PenurunanNoSegmentRitel::create([
                'regional_office' => $record['Regional Office'] ?? null,
                'kode_cabang_induk' => $record['Kode Cabang Induk'] ?? null,
                'cabang_induk' => $record['Cabang Induk'] ?? null,
                'kode_uker' => $record['Kode Uker'] ?? null,
                'unit_kerja' => $record['Unit Kerja'] ?? null,
                'cifno' => $record['CIFNO'] ?? null,
                'no_rekening' => $record['No Rekening'] ?? null,
                'penurunan' => $record['PENURUNAN'] ?? null,
                'product_type' => $record['Product Type'] ?? null,
                'nama_nasabah' => $record['Nama Nasabah'] ?? null,
                'segmentasi_bpr' => $record['Segmentasi BPR'] ?? null,
                'jenis_simpanan' => $record['Jenis Simpanan'] ?? null,
                'saldo_last_eom' => $record['Saldo Last EOM'] ?? null,
                'saldo_terupdate' => $record['Saldo Terupdate'] ?? null,
                'delta' => $record['Delta'] ?? null,
                'pn_slot_1' => $record['PN Slot 1'] ?? null,
                'pn_slot_2' => $record['PN Slot 2'] ?? null,
                'pn_slot_3' => $record['PN Slot 3'] ?? null,
                'pn_slot_4' => $record['PN Slot 4'] ?? null,
                'pn_slot_5' => $record['PN Slot 5'] ?? null,
                'pn_slot_6' => $record['PN Slot 6'] ?? null,
                'pn_slot_7' => $record['PN Slot 7'] ?? null,
                'pn_slot_8' => $record['PN Slot 8'] ?? null,
            ]);
        }
        
        $this->command->info('Data Penurunan No-Segment Ritel berhasil diimport!');
    }
}
