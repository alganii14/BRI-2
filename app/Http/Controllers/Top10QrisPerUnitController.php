<?php

namespace App\Http\Controllers;

use App\Models\Top10QrisPerUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Top10QrisPerUnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $data = Top10QrisPerUnit::when($year, function($query) use ($year) {
            return $query->whereYear('created_at', $year);
        })
        ->when($month, function($query) use ($month) {
            return $query->whereMonth('created_at', $month);
        })
        ->when($search, function($query) use ($search) {
            return $query->where('nama_merchant', 'like', "%{$search}%")
                        ->orWhere('no_rek', 'like', "%{$search}%")
                        ->orWhere('cif', 'like', "%{$search}%")
                        ->orWhere('storeid', 'like', "%{$search}%")
                        ->orWhere('brdesc', 'like', "%{$search}%");
        })
        ->orderBy('rank', 'asc')
        ->paginate(20);
        
        $availableYears = Top10QrisPerUnit::selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('top10-qris-per-unit.index', compact('data', 'search', 'month', 'year', 'availableYears'));
    }

    public function create()
    {
        return view('top10-qris-per-unit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rank' => 'nullable|string',
            'posisi' => 'nullable|string',
            'mainbr' => 'nullable|string',
            'mbdesc' => 'nullable|string',
            'branch' => 'nullable|string',
            'brdesc' => 'nullable|string',
            'storeid' => 'nullable|string',
            'nama_merchant' => 'nullable|string',
            'no_rek' => 'nullable|string',
            'cif' => 'nullable|string',
            'pn' => 'nullable|string',
            'pn_pemrakasa' => 'nullable|string',
            'akumulasi_sv_total' => 'nullable|string',
            'posisi_sv_total_september' => 'nullable|string',
            'akumulasi_trx_total' => 'nullable|string',
            'posisi_trx_total' => 'nullable|string',
            'saldo_posisi' => 'nullable|string',
            'ratas_saldo' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        Top10QrisPerUnit::create($validated);

        return redirect()->route('top10-qris-per-unit.index')
                        ->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Top10QrisPerUnit $top10QrisPerUnit)
    {
        return view('top10-qris-per-unit.show', compact('top10QrisPerUnit'));
    }

    public function edit(Top10QrisPerUnit $top10QrisPerUnit)
    {
        return view('top10-qris-per-unit.edit', compact('top10QrisPerUnit'));
    }

    public function update(Request $request, Top10QrisPerUnit $top10QrisPerUnit)
    {
        $validated = $request->validate([
            'rank' => 'nullable|string',
            'posisi' => 'nullable|string',
            'mainbr' => 'nullable|string',
            'mbdesc' => 'nullable|string',
            'branch' => 'nullable|string',
            'brdesc' => 'nullable|string',
            'storeid' => 'nullable|string',
            'nama_merchant' => 'nullable|string',
            'no_rek' => 'nullable|string',
            'cif' => 'nullable|string',
            'pn' => 'nullable|string',
            'pn_pemrakasa' => 'nullable|string',
            'akumulasi_sv_total' => 'nullable|string',
            'posisi_sv_total_september' => 'nullable|string',
            'akumulasi_trx_total' => 'nullable|string',
            'posisi_trx_total' => 'nullable|string',
            'saldo_posisi' => 'nullable|string',
            'ratas_saldo' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        $top10QrisPerUnit->update($validated);

        return redirect()->route('top10-qris-per-unit.index')
                        ->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Top10QrisPerUnit $top10QrisPerUnit)
    {
        $top10QrisPerUnit->delete();

        return redirect()->route('top10-qris-per-unit.index')
                        ->with('success', 'Data berhasil dihapus');
    }

    public function importForm()
    {
        return view('top10-qris-per-unit.import');
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
            
            // Check if header matches expected format (19 columns)
            if (count($header) !== 19) {
                return back()->with('error', 'Format CSV tidak sesuai. Pastikan CSV memiliki 19 kolom.');
            }

            // Delete existing data first (outside transaction)
            Top10QrisPerUnit::truncate();
            
            DB::beginTransaction();
            
            $batchSize = 1000;
            $batch = [];
            $rowCount = 0;
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== 19) {
                    continue; // Skip invalid rows
                }
                
                $batch[] = [
                    'rank' => $row[0] ?? null,
                    'posisi' => $row[1] ?? null,
                    'mainbr' => $row[2] ?? null,
                    'mbdesc' => $row[3] ?? null,
                    'branch' => $row[4] ?? null,
                    'brdesc' => $row[5] ?? null,
                    'storeid' => $row[6] ?? null,
                    'nama_merchant' => $row[7] ?? null,
                    'no_rek' => $row[8] ?? null,
                    'cif' => $row[9] ?? null,
                    'pn' => $row[10] ?? null,
                    'pn_pemrakasa' => $row[11] ?? null,
                    'akumulasi_sv_total' => $row[12] ?? null,
                    'posisi_sv_total_september' => $row[13] ?? null,
                    'akumulasi_trx_total' => $row[14] ?? null,
                    'posisi_trx_total' => $row[15] ?? null,
                    'saldo_posisi' => $row[16] ?? null,
                    'ratas_saldo' => $row[17] ?? null,
                    'alamat' => $row[18] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $rowCount++;
                
                if (count($batch) >= $batchSize) {
                    DB::table('top10_qris_per_units')->insert($batch);
                    $batch = [];
                }
            }
            
            // Insert remaining records
            if (!empty($batch)) {
                DB::table('top10_qris_per_units')->insert($batch);
            }
            
            fclose($handle);
            DB::commit();
            
            return redirect()->route('top10-qris-per-unit.index')
                           ->with('success', "Data berhasil diimport! Total {$rowCount} baris.");
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
