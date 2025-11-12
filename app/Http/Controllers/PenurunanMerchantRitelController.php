<?php

namespace App\Http\Controllers;

use App\Models\PenurunanMerchantRitel;
use Illuminate\Http\Request;
use DB;

class PenurunanMerchantRitelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanMerchantRitel::query();
        
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        if ($search) {
            $query->where('nama_nasabah', 'like', '%' . $search . '%')
                  ->orWhere('no_rekening', 'like', '%' . $search . '%')
                  ->orWhere('cifno', 'like', '%' . $search . '%')
                  ->orWhere('unit_kerja', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(20);
        $lastPage = $data->lastPage();
        
        $availableYears = PenurunanMerchantRitel::selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('penurunan-merchant-ritel.index', compact('data', 'lastPage', 'search', 'month', 'year', 'availableYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penurunan-merchant-ritel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        PenurunanMerchantRitel::create($validated);

        return redirect()->route('penurunan-merchant-ritel.index')
                        ->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenurunanMerchantRitel $penurunanMerchantRitel)
    {
        return view('penurunan-merchant-ritel.show', ['data' => $penurunanMerchantRitel]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenurunanMerchantRitel $penurunanMerchantRitel)
    {
        return view('penurunan-merchant-ritel.edit', ['data' => $penurunanMerchantRitel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenurunanMerchantRitel $penurunanMerchantRitel)
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

        $penurunanMerchantRitel->update($validated);

        return redirect()->route('penurunan-merchant-ritel.index')
                        ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenurunanMerchantRitel $penurunanMerchantRitel)
    {
        $penurunanMerchantRitel->delete();

        return redirect()->route('penurunan-merchant-ritel.index')
                        ->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Show the form for importing CSV
     */
    public function importForm()
    {
        return view('penurunan-merchant-ritel.import');
    }

    /**
     * Import CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        try {
            DB::beginTransaction();

            $handle = fopen($path, 'r');
            
            // Detect delimiter (semicolon or comma)
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            
            $header = fgetcsv($handle, 0, $delimiter); // Skip header row
            
            $batch = [];
            $batchSize = 1000; // Process 1000 rows at a time
            $totalInserted = 0;

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (count($row) >= 23 && !empty(array_filter($row))) {
                    $batch[] = [
                        'regional_office' => trim($row[0]) ?: null,
                        'kode_cabang_induk' => trim($row[1]) ?: null,
                        'cabang_induk' => trim($row[2]) ?: null,
                        'kode_uker' => trim($row[3]) ?: null,
                        'unit_kerja' => trim($row[4]) ?: null,
                        'cifno' => trim($row[5]) ?: null,
                        'no_rekening' => trim($row[6]) ?: null,
                        'penurunan' => trim($row[7]) ?: null,
                        'product_type' => trim($row[8]) ?: null,
                        'nama_nasabah' => trim($row[9]) ?: null,
                        'segmentasi_bpr' => trim($row[10]) ?: null,
                        'jenis_simpanan' => trim($row[11]) ?: null,
                        'saldo_last_eom' => trim($row[12]) ?: null,
                        'saldo_terupdate' => trim($row[13]) ?: null,
                        'delta' => trim($row[14]) ?: null,
                        'pn_slot_1' => trim($row[15]) ?: null,
                        'pn_slot_2' => trim($row[16]) ?: null,
                        'pn_slot_3' => trim($row[17]) ?: null,
                        'pn_slot_4' => trim($row[18]) ?: null,
                        'pn_slot_5' => trim($row[19]) ?: null,
                        'pn_slot_6' => trim($row[20]) ?: null,
                        'pn_slot_7' => trim($row[21]) ?: null,
                        'pn_slot_8' => trim($row[22]) ?: null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($batch) >= $batchSize) {
                        DB::table('penurunan_merchant_ritels')->insert($batch);
                        $totalInserted += count($batch);
                        $batch = [];
                    }
                }
            }

            // Insert remaining batch
            if (!empty($batch)) {
                DB::table('penurunan_merchant_ritels')->insert($batch);
                $totalInserted += count($batch);
            }

            fclose($handle);
            DB::commit();

            return redirect()->route('penurunan-merchant-ritel.index')
                            ->with('success', '✓ Import berhasil! Total data: ' . number_format($totalInserted, 0, ',', '.') . ' baris');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', '✗ Gagal mengimport CSV: ' . $e->getMessage());
        }
    }

    /**
     * Delete all records from the table.
     */
    public function deleteAll()
    {
        try {
            $count = PenurunanMerchantRitel::count();
            PenurunanMerchantRitel::truncate();
            
            return redirect()->route('penurunan-merchant-ritel.index')
                            ->with('success', '✓ Berhasil menghapus semua data! Total: ' . number_format($count, 0, ',', '.') . ' record telah dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', '✗ Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
