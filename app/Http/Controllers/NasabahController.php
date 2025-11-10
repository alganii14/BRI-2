<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use Illuminate\Support\Facades\DB;

class NasabahController extends Controller
{
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
        
        $query = Nasabah::query();
        
        // Search by CIFNO or nama_nasabah
        $query->where(function($q) use ($search) {
            $q->where('cifno', 'LIKE', "%{$search}%")
              ->orWhere('nama_nasabah', 'LIKE', "%{$search}%");
        });
        
        // Filter by KC and Unit jika diberikan
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
        
        $nasabah = $query->limit(50)
                        ->orderBy('nama_nasabah', 'asc')
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
        
        // Filter by KC and Unit jika diberikan
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
        
        $query = Nasabah::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('norek', 'like', "%{$search}%")
                  ->orWhere('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('kode_kc', 'like', "%{$search}%")
                  ->orWhere('nama_kc', 'like', "%{$search}%")
                  ->orWhere('kode_uker', 'like', "%{$search}%")
                  ->orWhere('nama_uker', 'like', "%{$search}%");
            });
        }
        
        // Filter by KC untuk Manager
        if ($user->isManager() && $user->kode_kanca) {
            $query->where('kode_kc', $user->kode_kanca);
        }
        
        $nasabahs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('nasabah.index', compact('nasabahs'));
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
