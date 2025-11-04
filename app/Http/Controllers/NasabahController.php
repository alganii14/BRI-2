<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;

class NasabahController extends Controller
{
    /**
     * Search nasabah by norek (for autocomplete)
     */
    public function searchByNorek(Request $request)
    {
        $search = $request->get('norek'); // Bisa norek atau nama
        $kode_kc = $request->get('kode_kc');
        $kode_uker = $request->get('kode_uker');
        
        if (!$search) {
            return response()->json([]);
        }
        
        $query = Nasabah::query();
        
        // Search by norek or nama_nasabah
        $query->where(function($q) use ($search) {
            $q->where('norek', 'LIKE', "%{$search}%")
              ->orWhere('nama_nasabah', 'LIKE', "%{$search}%");
        });
        
        // Filter by KC and Unit jika diberikan
        if ($kode_kc) {
            $query->where('kode_kc', $kode_kc);
        }
        
        if ($kode_uker) {
            $query->where('kode_uker', $kode_uker);
        }
        
        $nasabah = $query->limit(50)
                        ->orderBy('nama_nasabah', 'asc')
                        ->get(['id', 'norek', 'nama_nasabah', 'segmen_nasabah', 'kode_kc', 'nama_kc', 'kode_uker', 'nama_uker']);
        
        return response()->json($nasabah);
    }

    /**
     * Get nasabah by exact norek
     */
    public function getByNorek(Request $request)
    {
        $norek = $request->get('norek');
        $kode_kc = $request->get('kode_kc');
        $kode_uker = $request->get('kode_uker');
        
        $query = Nasabah::where('norek', $norek);
        
        // Filter by KC and Unit jika diberikan
        if ($kode_kc) {
            $query->where('kode_kc', $kode_kc);
        }
        
        if ($kode_uker) {
            $query->where('kode_uker', $kode_uker);
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
    public function index()
    {
        $user = auth()->user();
        
        $nasabahs = Nasabah::query()
            ->when($user->isManager() && $user->kode_kanca, function($query) use ($user) {
                // Manager hanya lihat nasabah di KC mereka
                return $query->where('kode_kc', $user->kode_kanca);
            })
            // Admin melihat semua nasabah
            ->orderBy('nama_nasabah', 'asc')
            ->paginate(20);
            
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
            'norek' => 'required|string|max:50',
            'nama_nasabah' => 'required|string|max:255',
            'segmen_nasabah' => 'required|string',
            'kode_kc' => 'required|string|max:20',
            'nama_kc' => 'required|string|max:255',
            'kode_uker' => 'required|string|max:20',
            'nama_uker' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        Nasabah::create($validated);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nasabah = Nasabah::with('aktivitas')->findOrFail($id);
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
            'norek' => 'required|string|max:50',
            'nama_nasabah' => 'required|string|max:255',
            'segmen_nasabah' => 'required|string',
            'kode_kc' => 'required|string|max:20',
            'nama_kc' => 'required|string|max:255',
            'kode_uker' => 'required|string|max:20',
            'nama_uker' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $nasabah->update($validated);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil diupdate!');
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

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dihapus!');
    }
}
