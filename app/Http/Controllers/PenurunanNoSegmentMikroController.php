<?php

namespace App\Http\Controllers;

use App\Models\PenurunanNoSegmentMikro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenurunanNoSegmentMikroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PenurunanNoSegmentMikro::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        $penurunanNoSegmentMikros = $query->latest()->paginate(20);
        
        return view('penurunan-no-segment-mikro.index', compact('penurunanNoSegmentMikros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('penurunan-no-segment-mikro.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        PenurunanNoSegmentMikro::create($validated);

        return redirect()->route('penurunan-no-segment-mikro.index')
            ->with('success', 'Data penurunan no-segment mikro berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PenurunanNoSegmentMikro $penurunanNoSegmentMikro)
    {
        return view('penurunan-no-segment-mikro.show', compact('penurunanNoSegmentMikro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PenurunanNoSegmentMikro $penurunanNoSegmentMikro)
    {
        return view('penurunan-no-segment-mikro.edit', compact('penurunanNoSegmentMikro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenurunanNoSegmentMikro $penurunanNoSegmentMikro)
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

        $penurunanNoSegmentMikro->update($validated);

        return redirect()->route('penurunan-no-segment-mikro.index')
            ->with('success', 'Data penurunan no-segment mikro berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenurunanNoSegmentMikro $penurunanNoSegmentMikro)
    {
        $penurunanNoSegmentMikro->delete();

        return redirect()->route('penurunan-no-segment-mikro.index')
            ->with('success', 'Data penurunan no-segment mikro berhasil dihapus.');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('penurunan-no-segment-mikro.import');
    }

    /**
     * Import CSV file
     */
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
                        DB::table('penurunan_no_segment_mikros')->insert($batch);
                        $totalInserted += count($batch);
                        $batch = [];
                    }
                }
            }

            // Insert remaining batch
            if (!empty($batch)) {
                DB::table('penurunan_no_segment_mikros')->insert($batch);
                $totalInserted += count($batch);
            }

            fclose($handle);
            DB::commit();

            return redirect()->route('penurunan-no-segment-mikro.index')
                            ->with('success', '✓ Import berhasil! Total data: ' . number_format($totalInserted, 0, ',', '.') . ' baris');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', '✗ Gagal mengimport CSV: ' . $e->getMessage());
        }
    }
}
