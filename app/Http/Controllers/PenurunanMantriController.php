<?php

namespace App\Http\Controllers;

use App\Models\PenurunanMantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenurunanMantriController extends Controller
{
    public function index(Request $request)
    {
        $query = PenurunanMantri::query();
        
        $month = $request->get('month');
        $year = $request->get('year');
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        $penurunanMantris = $query->latest()->paginate(20);
        
        // Get available years
        $availableYears = PenurunanMantri::selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('penurunan-mantri.index', compact('penurunanMantris', 'month', 'year', 'availableYears'));
    }

    public function create()
    {
        return view('penurunan-mantri.create');
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
            'ytd' => 'nullable|string',
            'product_type' => 'nullable|string',
            'nama_nasabah' => 'nullable|string',
            'jenis_nasabah' => 'nullable|string',
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

        PenurunanMantri::create($validated);

        return redirect()->route('penurunan-mantri.index')
            ->with('success', 'Data penurunan mantri berhasil ditambahkan.');
    }

    public function show(PenurunanMantri $penurunanMantri)
    {
        return view('penurunan-mantri.show', compact('penurunanMantri'));
    }

    public function edit(PenurunanMantri $penurunanMantri)
    {
        return view('penurunan-mantri.edit', compact('penurunanMantri'));
    }

    public function update(Request $request, PenurunanMantri $penurunanMantri)
    {
        $validated = $request->validate([
            'regional_office' => 'nullable|string',
            'kode_cabang_induk' => 'nullable|string',
            'cabang_induk' => 'nullable|string',
            'kode_uker' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'cifno' => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'ytd' => 'nullable|string',
            'product_type' => 'nullable|string',
            'nama_nasabah' => 'nullable|string',
            'jenis_nasabah' => 'nullable|string',
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

        $penurunanMantri->update($validated);

        return redirect()->route('penurunan-mantri.index')
            ->with('success', 'Data penurunan mantri berhasil diupdate.');
    }

    public function destroy(PenurunanMantri $penurunanMantri)
    {
        $penurunanMantri->delete();

        return redirect()->route('penurunan-mantri.index')
            ->with('success', 'Data penurunan mantri berhasil dihapus.');
    }

    public function importForm()
    {
        return view('penurunan-mantri.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        try {
            DB::beginTransaction();

            $handle = fopen($path, 'r');
            $header = fgetcsv($handle); // Skip header row
            
            $batch = [];
            $batchSize = 1000; // Process 1000 rows at a time
            $totalInserted = 0;

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 24 && !empty(array_filter($row))) {
                    $batch[] = [
                        'regional_office' => trim($row[0]) ?: null,
                        'kode_cabang_induk' => trim($row[1]) ?: null,
                        'cabang_induk' => trim($row[2]) ?: null,
                        'kode_uker' => trim($row[3]) ?: null,
                        'unit_kerja' => trim($row[4]) ?: null,
                        'cifno' => trim($row[5]) ?: null,
                        'no_rekening' => trim($row[6]) ?: null,
                        'ytd' => trim($row[7]) ?: null,
                        'product_type' => trim($row[8]) ?: null,
                        'nama_nasabah' => trim($row[9]) ?: null,
                        'jenis_nasabah' => trim($row[10]) ?: null,
                        'segmentasi_bpr' => trim($row[11]) ?: null,
                        'jenis_simpanan' => trim($row[12]) ?: null,
                        'saldo_last_eom' => trim($row[13]) ?: null,
                        'saldo_terupdate' => trim($row[14]) ?: null,
                        'delta' => trim($row[15]) ?: null,
                        'pn_slot_1' => trim($row[16]) ?: null,
                        'pn_slot_2' => trim($row[17]) ?: null,
                        'pn_slot_3' => trim($row[18]) ?: null,
                        'pn_slot_4' => trim($row[19]) ?: null,
                        'pn_slot_5' => trim($row[20]) ?: null,
                        'pn_slot_6' => trim($row[21]) ?: null,
                        'pn_slot_7' => trim($row[22]) ?: null,
                        'pn_slot_8' => trim($row[23]) ?: null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($batch) >= $batchSize) {
                        DB::table('penurunan_mantris')->insert($batch);
                        $totalInserted += count($batch);
                        $batch = [];
                    }
                }
            }

            // Insert remaining batch
            if (!empty($batch)) {
                DB::table('penurunan_mantris')->insert($batch);
                $totalInserted += count($batch);
            }

            fclose($handle);
            DB::commit();

            return redirect()->route('penurunan-mantri.index')
                            ->with('success', '✓ Import berhasil! Total data: ' . number_format($totalInserted, 0, ',', '.') . ' baris');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', '✗ Gagal mengimport CSV: ' . $e->getMessage());
        }
    }
}
