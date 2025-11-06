<?php

namespace App\Http\Controllers;

use App\Models\PenurunanSmeRitel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenurunanSmeRitelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $data = PenurunanSmeRitel::when($search, function($query) use ($search) {
            return $query->where('nama_nasabah', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('cifno', 'like', "%{$search}%")
                        ->orWhere('unit_kerja', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        
        return view('penurunan-sme-ritel.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('penurunan-sme-ritel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'regional_office' => 'nullable|string',
            'kode_cabang_induk' => 'nullable|string',
            'cabang_induk' => 'nullable|string',
            'kode_uker' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'cifno' => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'penurunan' => 'nullable|string',
            'product_type' => 'nullable|string',
            'nama_nasabah' => 'nullable|string',
            'segmentasi_bpr' => 'nullable|string',
            'jenis_simpanan' => 'nullable|string',
            'saldo_last_eom' => 'nullable|string',
            'saldo_terupdate' => 'nullable|string',
            'delta' => 'nullable|string',
            'pn_slot_1' => 'nullable|string',
            'pn_slot_2' => 'nullable|string',
            'pn_slot_3' => 'nullable|string',
            'pn_slot_4' => 'nullable|string',
            'pn_slot_5' => 'nullable|string',
            'pn_slot_6' => 'nullable|string',
            'pn_slot_7' => 'nullable|string',
            'pn_slot_8' => 'nullable|string',
        ]);

        PenurunanSmeRitel::create($validated);

        return redirect()->route('penurunan-sme-ritel.index')
                        ->with('success', 'Data berhasil ditambahkan');
    }

    public function show(PenurunanSmeRitel $penurunanSmeRitel)
    {
        return view('penurunan-sme-ritel.show', compact('penurunanSmeRitel'));
    }

    public function edit(PenurunanSmeRitel $penurunanSmeRitel)
    {
        return view('penurunan-sme-ritel.edit', compact('penurunanSmeRitel'));
    }

    public function update(Request $request, PenurunanSmeRitel $penurunanSmeRitel)
    {
        $validated = $request->validate([
            'regional_office' => 'nullable|string',
            'kode_cabang_induk' => 'nullable|string',
            'cabang_induk' => 'nullable|string',
            'kode_uker' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'cifno' => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'penurunan' => 'nullable|string',
            'product_type' => 'nullable|string',
            'nama_nasabah' => 'nullable|string',
            'segmentasi_bpr' => 'nullable|string',
            'jenis_simpanan' => 'nullable|string',
            'saldo_last_eom' => 'nullable|string',
            'saldo_terupdate' => 'nullable|string',
            'delta' => 'nullable|string',
            'pn_slot_1' => 'nullable|string',
            'pn_slot_2' => 'nullable|string',
            'pn_slot_3' => 'nullable|string',
            'pn_slot_4' => 'nullable|string',
            'pn_slot_5' => 'nullable|string',
            'pn_slot_6' => 'nullable|string',
            'pn_slot_7' => 'nullable|string',
            'pn_slot_8' => 'nullable|string',
        ]);

        $penurunanSmeRitel->update($validated);

        return redirect()->route('penurunan-sme-ritel.index')
                        ->with('success', 'Data berhasil diupdate');
    }

    public function destroy(PenurunanSmeRitel $penurunanSmeRitel)
    {
        $penurunanSmeRitel->delete();

        return redirect()->route('penurunan-sme-ritel.index')
                        ->with('success', 'Data berhasil dihapus');
    }

    public function importForm()
    {
        return view('penurunan-sme-ritel.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $file = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');
            
            // Skip header row
            $header = fgetcsv($handle);
            
            // Check if header matches expected format (23 columns)
            if (count($header) !== 23) {
                return back()->with('error', 'Format CSV tidak sesuai. Pastikan CSV memiliki 23 kolom.');
            }

            // Delete existing data first (outside transaction)
            PenurunanSmeRitel::truncate();
            
            DB::beginTransaction();
            
            $batchSize = 1000;
            $batch = [];
            $rowCount = 0;
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== 23) {
                    continue; // Skip invalid rows
                }
                
                $batch[] = [
                    'regional_office' => $row[0] ?? null,
                    'kode_cabang_induk' => $row[1] ?? null,
                    'cabang_induk' => $row[2] ?? null,
                    'kode_uker' => $row[3] ?? null,
                    'unit_kerja' => $row[4] ?? null,
                    'cifno' => $row[5] ?? null,
                    'no_rekening' => $row[6] ?? null,
                    'penurunan' => $row[7] ?? null,
                    'product_type' => $row[8] ?? null,
                    'nama_nasabah' => $row[9] ?? null,
                    'segmentasi_bpr' => $row[10] ?? null,
                    'jenis_simpanan' => $row[11] ?? null,
                    'saldo_last_eom' => $row[12] ?? null,
                    'saldo_terupdate' => $row[13] ?? null,
                    'delta' => $row[14] ?? null,
                    'pn_slot_1' => $row[15] ?? null,
                    'pn_slot_2' => $row[16] ?? null,
                    'pn_slot_3' => $row[17] ?? null,
                    'pn_slot_4' => $row[18] ?? null,
                    'pn_slot_5' => $row[19] ?? null,
                    'pn_slot_6' => $row[20] ?? null,
                    'pn_slot_7' => $row[21] ?? null,
                    'pn_slot_8' => $row[22] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $rowCount++;
                
                if (count($batch) >= $batchSize) {
                    DB::table('penurunan_sme_ritels')->insert($batch);
                    $batch = [];
                }
            }
            
            // Insert remaining records
            if (!empty($batch)) {
                DB::table('penurunan_sme_ritels')->insert($batch);
            }
            
            fclose($handle);
            DB::commit();
            
            return redirect()->route('penurunan-sme-ritel.index')
                           ->with('success', "Data berhasil diimport! Total {$rowCount} baris.");
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
