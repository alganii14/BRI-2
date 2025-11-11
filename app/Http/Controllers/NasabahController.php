<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\PenurunanBrilink;
use App\Models\PenurunanMantri;
use App\Models\PenurunanMerchantMikro;
use App\Models\PenurunanMerchantRitel;
use App\Models\PenurunanNoSegmentMikro;
use App\Models\PenurunanNoSegmentRitel;
use App\Models\PenurunanSmeRitel;
use App\Models\Top10QrisPerUnit;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
    /**
     * Search nasabah from pipeline tables based on strategy
     */
    public function searchPipeline(Request $request)
    {
        $search = $request->get('search');
        $kode_kc = $request->get('kode_kc');
        $kode_uker = $request->get('kode_uker');
        $strategy = $request->get('strategy');
        $load_all = $request->get('load_all'); // Parameter untuk load semua data
        
        if (!$strategy) {
            return response()->json([]);
        }
        
        // Jika load_all atau search kosong, tidak perlu minimum 2 karakter
        if (!$load_all && $search && strlen($search) < 2) {
            return response()->json([]);
        }
        
        // Tentukan model berdasarkan strategy
        $model = null;
        $isQris = false;
        
        switch ($strategy) {
            case 'Penurunan Brilink':
                $model = PenurunanBrilink::class;
                break;
            case 'Penurunan Mantri':
                $model = PenurunanMantri::class;
                break;
            case 'Penurunan Merchant Mikro':
                $model = PenurunanMerchantMikro::class;
                break;
            case 'Penurunan Merchant Ritel':
                $model = PenurunanMerchantRitel::class;
                break;
            case 'Penurunan No-Segment Mikro':
                $model = PenurunanNoSegmentMikro::class;
                break;
            case 'Penurunan No-Segment Ritel':
                $model = PenurunanNoSegmentRitel::class;
                break;
            case 'Penurunan SME Ritel':
                $model = PenurunanSmeRitel::class;
                break;
            case 'Top 10 QRIS Per Unit':
                $model = Top10QrisPerUnit::class;
                $isQris = true;
                break;
            default:
                return response()->json([]);
        }
        
        $query = $model::query();
        
        if ($isQris) {
            // Top 10 QRIS memiliki struktur field berbeda
            // Filter by KC first
            if ($kode_kc) {
                $query->where('mainbr', $kode_kc);
            }
            
            if ($kode_uker) {
                // Check if multiple units (comma-separated)
                if (strpos($kode_uker, ',') !== false) {
                    // Multiple units
                    $unitArray = array_map('trim', explode(',', $kode_uker));
                    $query->whereIn('branch', $unitArray);
                } else {
                    // Single unit
                    $query->where('branch', $kode_uker);
                }
            }
            
            // Search by CIF or nama_merchant (hanya jika ada search term)
            if ($search && strlen($search) >= 2) {
                $query->where(function($q) use ($search) {
                    $q->where('cif', 'LIKE', "{$search}%")
                      ->orWhere('nama_merchant', 'LIKE', "%{$search}%");
                });
            }
            
            $results = $query->limit(100)  // Batasi 100 hasil untuk load all
                            ->orderBy('cif', 'asc')
                            ->get()
                            ->map(function($item) {
                                // Ensure numeric values are properly formatted
                                return [
                                    'id' => $item->id,
                                    'cifno' => $item->cif,
                                    'no_rekening' => $item->no_rek,
                                    'nama_nasabah' => $item->nama_merchant,
                                    'kode_cabang_induk' => $item->mainbr,
                                    'cabang_induk' => $item->mbdesc,
                                    'kode_uker' => $item->branch,
                                    'unit_kerja' => $item->brdesc,
                                    'saldo_terupdate' => floatval($item->saldo_posisi ?? 0),
                                ];
                            });
            
            return response()->json($results);
            
        } else {
            // Tabel penurunan lainnya
            // Filter by KC first (paling penting untuk performance)
            if ($kode_kc) {
                $query->where('kode_cabang_induk', $kode_kc);
            }
            
            if ($kode_uker) {
                // Check if multiple units (comma-separated)
                if (strpos($kode_uker, ',') !== false) {
                    // Multiple units
                    $unitArray = array_map('trim', explode(',', $kode_uker));
                    $query->whereIn('kode_uker', $unitArray);
                } else {
                    // Single unit
                    $query->where('kode_uker', $kode_uker);
                }
            }
            
            // Search by CIFNO or nama_nasabah (hanya jika ada search term)
            if ($search && strlen($search) >= 2) {
                $query->where(function($q) use ($search) {
                    $q->where('cifno', 'LIKE', "{$search}%")  // Exact start match (lebih cepat)
                      ->orWhere('nama_nasabah', 'LIKE', "%{$search}%");
                });
            }
            
            $results = $query->limit(100)  // Batasi 100 hasil untuk load all
                            ->orderBy('cifno', 'asc')
                            ->get()
                            ->map(function($item) {
                                // Ensure numeric values are properly formatted
                                return [
                                    'id' => $item->id,
                                    'cifno' => $item->cifno,
                                    'no_rekening' => $item->no_rekening,
                                    'nama_nasabah' => $item->nama_nasabah,
                                    'kode_cabang_induk' => $item->kode_cabang_induk,
                                    'cabang_induk' => $item->cabang_induk,
                                    'kode_uker' => $item->kode_uker,
                                    'unit_kerja' => $item->unit_kerja,
                                    'saldo_terupdate' => floatval($item->saldo_terupdate ?? $item->saldo_last_eom ?? 0),
                                    'saldo_last_eom' => floatval($item->saldo_last_eom ?? 0),
                                ];
                            });
            
            return response()->json($results);
        }
    }

    /**
     * Search nasabah by CIFNO (for autocomplete)
     */
    public function searchByNorek(Request $request)
    {
        $search = $request->get('norek'); // Parameter name kept for compatibility, but searches CIFNO
        $kode_kc = $request->get('kode_kc');
        $kode_uker = $request->get('kode_uker');
        
        if (!$search) {
            return response()->json([]);
        }
        
        // Minimum 2 karakter untuk search
        if (strlen($search) < 2) {
            return response()->json([]);
        }
        
        $query = Nasabah::query();
        
        // Filter by KC first (paling penting untuk performance)
        if ($kode_kc) {
            $query->where('kode_kc', $kode_kc);
        }
        
        if ($kode_uker) {
            // Check if multiple units (comma-separated)
            if (strpos($kode_uker, ',') !== false) {
                // Multiple units
                $unitArray = array_map('trim', explode(',', $kode_uker));
                $query->whereIn('kode_uker', $unitArray);
            } else {
                // Single unit
                $query->where('kode_uker', $kode_uker);
            }
        }
        
        // Search by CIFNO or nama_nasabah (setelah filter KC dan Unit)
        $query->where(function($q) use ($search) {
            $q->where('cifno', 'LIKE', "{$search}%")  // Exact start match (lebih cepat)
              ->orWhere('nama_nasabah', 'LIKE', "%{$search}%");
        });
        
        $nasabah = $query->limit(30)  // Batasi hanya 30 hasil
                        ->orderBy('cifno', 'asc')
                        ->get(['id', 'cifno', 'norek', 'nama_nasabah', 'segmen_nasabah', 'kode_kc', 'nama_kc', 'kode_uker', 'nama_uker']);
        
        return response()->json($nasabah);
    }

    /**
     * Get nasabah by exact CIFNO
     */
    public function getByNorek(Request $request)
    {
        $norek = $request->get('norek'); // Parameter kept for compatibility, but searches by CIFNO
        $kode_kc = $request->get('kode_kc');
        $kode_uker = $request->get('kode_uker');
        
        $query = Nasabah::where('cifno', $norek);
        
        // Filter by KC first (paling penting untuk performance)
        if ($kode_kc) {
            $query->where('kode_kc', $kode_kc);
        }
        
        if ($kode_uker) {
            // Check if multiple units (comma-separated)
            if (strpos($kode_uker, ',') !== false) {
                // Multiple units - use first match
                $unitArray = array_map('trim', explode(',', $kode_uker));
                $query->whereIn('kode_uker', $unitArray);
            } else {
                // Single unit
                $query->where('kode_uker', $kode_uker);
            }
        }
        
        $nasabah = $query->first();
        
        if ($nasabah) {
            return response()->json([
                'found' => true,
                'data' => $nasabah
            ]);
        }
        
        return response()->json([
            'found' => false
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // OPTIMASI: Select hanya kolom yang dibutuhkan untuk mengurangi memory usage
        $query = Nasabah::select([
            'id', 'norek', 'cifno', 'nama_nasabah', 'segmen_nasabah',
            'kode_kc', 'nama_kc', 'kode_uker', 'nama_uker', 'created_at'
        ]);
        
        // OPTIMASI: Filter by KC terlebih dahulu (paling selektif)
        if ($user->isManager() && $user->kode_kanca) {
            $query->where('kode_kc', $user->kode_kanca);
        } elseif ($request->has('kode_kc') && !empty($request->kode_kc)) {
            // Filter KC dari form filter
            $query->where('kode_kc', $request->kode_kc);
        }
        
        // Filter by Unit (jika ada)
        if ($request->has('kode_uker') && !empty($request->kode_uker)) {
            $query->where('kode_uker', $request->kode_uker);
        }
        
        // Search functionality (setelah filter KC/Unit)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            
            // OPTIMASI: Minimum 2 karakter untuk search
            if (strlen($search) >= 2) {
                $query->where(function($q) use ($search) {
                    // Gunakan exact start match untuk CIFNO dan norek (lebih cepat)
                    $q->where('norek', 'LIKE', "{$search}%")
                      ->orWhere('cifno', 'LIKE', "{$search}%")
                      ->orWhere('nama_nasabah', 'LIKE', "%{$search}%")
                      ->orWhere('nama_kc', 'LIKE', "%{$search}%")
                      ->orWhere('nama_uker', 'LIKE', "%{$search}%");
                });
            }
        }
        
        // OPTIMASI: Gunakan simplePaginate (TIDAK HITUNG TOTAL - lebih cepat!)
        $perPage = min((int)$request->get('per_page', 50), 200); // Max 200 per page
        
        // SimplePaginate tidak perlu COUNT(*) jadi jauh lebih cepat
        $nasabahs = $query->orderBy('created_at', 'desc')->simplePaginate($perPage);
        
        // OPTIMASI: Ambil KC list dari table uker (lebih kecil daripada nasabahs)
        $kcList = \DB::table('ukers')
            ->select('kode_kanca as kode_kc', 'kanca as nama_kc')
            ->whereNotNull('kode_kanca')
            ->distinct()
            ->orderBy('kanca')
            ->get();
        
        // Ambil Unit list berdasarkan KC yang dipilih (jika ada)
        $ukerList = collect();
        if ($request->has('kode_kc') && !empty($request->kode_kc)) {
            $ukerList = \DB::table('ukers')
                ->select('kode_sub_kanca as kode_uker', 'sub_kanca as nama_uker')
                ->where('kode_kanca', $request->kode_kc)
                ->whereNotNull('kode_sub_kanca')
                ->distinct()
                ->orderBy('sub_kanca')
                ->get();
        }
        
        return view('nasabah.index', compact('nasabahs', 'kcList', 'ukerList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('nasabah.create');
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
            'norek' => 'required|string|max:255',
            'cifno' => 'nullable|string|max:255',
            'nama_nasabah' => 'required|string|max:255',
            'segmen_nasabah' => 'required|string|max:255',
            'kode_kc' => 'required|string|max:50',
            'nama_kc' => 'required|string|max:255',
            'kode_uker' => 'required|string|max:50',
            'nama_uker' => 'required|string|max:255',
        ]);

        Nasabah::create($validated);

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return view('nasabah.show', compact('nasabah'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return view('nasabah.edit', compact('nasabah'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $validated = $request->validate([
            'norek' => 'required|string|max:255',
            'cifno' => 'nullable|string|max:255',
            'nama_nasabah' => 'required|string|max:255',
            'segmen_nasabah' => 'required|string|max:255',
            'kode_kc' => 'required|string|max:50',
            'nama_kc' => 'required|string|max:255',
            'kode_uker' => 'required|string|max:50',
            'nama_uker' => 'required|string|max:255',
        ]);

        $nasabah->update($validated);

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->delete();

        return redirect()->route('nasabah.index')
            ->with('success', 'Data nasabah berhasil dihapus!');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('nasabah.import');
    }

    /**
     * Import CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:102400', // max 100MB
        ]);

        try {
            $file = $request->file('csv_file');
            $handle = fopen($file->getPathname(), 'r');
            
            // Skip header row
            $header = fgetcsv($handle, 10000, ';');
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            try {
                while (($row = fgetcsv($handle, 10000, ';')) !== false) {
                    // Skip if row is empty
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    try {
                        // Check if record already exists based on norek
                        $existing = Nasabah::where('norek', $row[0])->first();
                        
                        $data = [
                            'norek' => $row[0] ?? null,
                            'cifno' => $row[1] ?? null,
                            'nama_nasabah' => $row[2] ?? null,
                            'segmen_nasabah' => $row[3] ?? null,
                            'kode_kc' => $row[4] ?? null,
                            'nama_kc' => $row[5] ?? null,
                            'kode_uker' => $row[6] ?? null,
                            'nama_uker' => $row[7] ?? null,
                        ];
                        
                        if ($existing) {
                            $existing->update($data);
                            $updated++;
                        } else {
                            Nasabah::create($data);
                            $imported++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Error on row: " . implode(', ', $row) . " - " . $e->getMessage();
                    }
                }
                
                DB::commit();
                fclose($handle);
                
                $message = "Berhasil import {$imported} data baru";
                if ($updated > 0) {
                    $message .= " dan update {$updated} data existing";
                }
                if (count($errors) > 0) {
                    $message .= ". " . count($errors) . " error terjadi.";
                }
                
                return redirect()->route('nasabah.index')->with('success', $message);
                
            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
                throw $e;
            }
            
        } catch (\Exception $e) {
            return redirect()->route('nasabah.index')
                ->with('error', 'Gagal import CSV: ' . $e->getMessage());
        }
    }

    /**
     * Delete all records
     */
    public function deleteAll()
    {
        try {
            Nasabah::truncate();
            return redirect()->route('nasabah.index')
                ->with('success', 'Semua data nasabah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('nasabah.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
