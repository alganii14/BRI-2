<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\RMFT;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin bisa lihat semua aktivitas dengan filter
            $query = Aktivitas::with(['rmft', 'assignedBy']);
            
            // Filter per KC
            if ($request->filled('kode_kc')) {
                $query->where('kode_kc', $request->kode_kc);
            }
            
            // Filter per Unit
            if ($request->filled('kode_uker')) {
                $query->where('kode_uker', $request->kode_uker);
            }
            
            $aktivitas = $query->orderBy('tanggal', 'desc')->paginate(20);
            
            // Get list KC dan Unit untuk dropdown filter
            $listKC = Aktivitas::select('kode_kc', 'nama_kc')
                               ->distinct()
                               ->orderBy('nama_kc')
                               ->get();
            
            $listUnit = Aktivitas::select('kode_uker', 'nama_uker', 'kode_kc')
                                 ->distinct()
                                 ->orderBy('nama_uker')
                                 ->get();
            
            return view('aktivitas.index', compact('aktivitas', 'listKC', 'listUnit'));
            
        } elseif ($user->isManager()) {
            // Manager hanya lihat aktivitas di Kanca mereka
            $aktivitas = Aktivitas::with(['rmft', 'assignedBy'])
                                  ->where('kode_kc', $user->kode_kanca)
                                  ->orderBy('tanggal', 'desc')
                                  ->paginate(20);
            
            return view('aktivitas.index', compact('aktivitas'));
        } else {
            // RMFT lihat aktivitas mereka sendiri
            $aktivitas = Aktivitas::with('assignedBy')
                                  ->where('rmft_id', $user->rmft_id)
                                  ->orderBy('tanggal', 'desc')
                                  ->paginate(20);
            
            return view('aktivitas.index', compact('aktivitas'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $rmftData = null;
        $rmftList = [];
        
        // Get RMFT data if user is RMFT
        if ($user->isRMFT() && $user->rmft_id) {
            $rmftData = RMFT::with('ukerRelation')->find($user->rmft_id);
        }
        
        // Get RMFT list if user is Manager (only from their Kanca)
        if ($user->isManager() && $user->kode_kanca) {
            $rmftList = User::where('role', 'rmft')
                           ->where('kode_kanca', $user->kode_kanca)
                           ->with('rmftData.ukerRelation')
                           ->orderBy('name', 'asc')
                           ->get();
        }
        
        // Get RMFT list if user is Admin (all RMFT)
        if ($user->isAdmin()) {
            $rmftList = User::where('role', 'rmft')
                           ->with('rmftData.ukerRelation')
                           ->orderBy('name', 'asc')
                           ->get();
        }
        
        return view('aktivitas.create', compact('rmftData', 'rmftList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'rmft_id' => 'required|exists:rmfts,id',
            'nama_rmft' => 'required|string',
            'pn' => 'required|string',
            'kode_kc' => 'required|string',
            'nama_kc' => 'required|string',
            'kode_uker' => 'required|string',
            'nama_uker' => 'required|string',
            'kode_uker_list' => 'nullable|string',
            'nama_uker_list' => 'nullable|string',
            'kelompok' => 'required|string',
            'strategy_pipeline' => 'required|string',
            'rencana_aktivitas' => 'required|string',
            'segmen_nasabah' => 'required|string',
            'nama_nasabah' => 'required|string',
            'norek' => 'required|string',
            'rp_jumlah' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Check if multiple units are selected
        $multipleUnits = !empty($validated['kode_uker_list']) && !empty($validated['nama_uker_list']);
        
        if ($multipleUnits) {
            // Split units
            $kodeUkerArray = explode(',', $validated['kode_uker_list']);
            $namaUkerArray = explode(',', $validated['nama_uker_list']);
            
            $createdCount = 0;
            
            foreach ($kodeUkerArray as $index => $kodeUker) {
                $namaUker = $namaUkerArray[$index] ?? '';
                
                if (empty($kodeUker) || empty($namaUker)) {
                    continue;
                }
                
                // Check if nasabah exists dengan KC dan Unit yang sama
                $nasabah = Nasabah::where('norek', $validated['norek'])
                                  ->where('kode_kc', $validated['kode_kc'])
                                  ->where('kode_uker', trim($kodeUker))
                                  ->first();
                
                if (!$nasabah) {
                    // Buat nasabah baru dengan KC dan Unit
                    $nasabah = Nasabah::create([
                        'norek' => $validated['norek'],
                        'nama_nasabah' => $validated['nama_nasabah'],
                        'segmen_nasabah' => $validated['segmen_nasabah'],
                        'kode_kc' => $validated['kode_kc'],
                        'nama_kc' => $validated['nama_kc'],
                        'kode_uker' => trim($kodeUker),
                        'nama_uker' => trim($namaUker),
                    ]);
                }
                
                // Create activity for this unit
                $activityData = [
                    'tanggal' => $validated['tanggal'],
                    'rmft_id' => $validated['rmft_id'],
                    'nama_rmft' => $validated['nama_rmft'],
                    'pn' => $validated['pn'],
                    'kode_kc' => $validated['kode_kc'],
                    'nama_kc' => $validated['nama_kc'],
                    'kode_uker' => trim($kodeUker),
                    'nama_uker' => trim($namaUker),
                    'kelompok' => $validated['kelompok'],
                    'strategy_pipeline' => $validated['strategy_pipeline'],
                    'rencana_aktivitas' => $validated['rencana_aktivitas'],
                    'segmen_nasabah' => $validated['segmen_nasabah'],
                    'nama_nasabah' => $validated['nama_nasabah'],
                    'norek' => $validated['norek'],
                    'rp_jumlah' => $validated['rp_jumlah'],
                    'keterangan' => $validated['keterangan'],
                    'nasabah_id' => $nasabah->id,
                ];
                
                // Jika Manager atau Admin yang membuat, ini adalah assignment
                if ($user->isManager() || $user->isAdmin()) {
                    $activityData['assigned_by'] = $user->id;
                    $activityData['tipe'] = 'assigned';
                } else {
                    $activityData['tipe'] = 'self';
                }
                
                Aktivitas::create($activityData);
                $createdCount++;
            }
            
            return redirect()->route('aktivitas.index')->with('success', "Berhasil membuat {$createdCount} aktivitas untuk {$createdCount} unit berbeda!");
            
        } else {
            // Single unit - existing logic
            $nasabah = Nasabah::where('norek', $validated['norek'])
                              ->where('kode_kc', $validated['kode_kc'])
                              ->where('kode_uker', $validated['kode_uker'])
                              ->first();
            
            if (!$nasabah) {
                // Cek apakah norek sudah ada tapi dengan KC/Unit berbeda
                $existingNasabah = Nasabah::where('norek', $validated['norek'])->first();
                
                if ($existingNasabah) {
                    return back()->withErrors([
                        'norek' => 'Norek ini terdaftar di KC: ' . $existingNasabah->nama_kc . ' / Unit: ' . $existingNasabah->nama_uker . '. Norek harus sesuai dengan KC dan Unit RMFT.'
                    ])->withInput();
                }
                
                // Buat nasabah baru dengan KC dan Unit
                $nasabah = Nasabah::create([
                    'norek' => $validated['norek'],
                    'nama_nasabah' => $validated['nama_nasabah'],
                    'segmen_nasabah' => $validated['segmen_nasabah'],
                    'kode_kc' => $validated['kode_kc'],
                    'nama_kc' => $validated['nama_kc'],
                    'kode_uker' => $validated['kode_uker'],
                    'nama_uker' => $validated['nama_uker'],
                ]);
            }
            
            $validated['nasabah_id'] = $nasabah->id;
            
            // Jika Manager atau Admin yang membuat, ini adalah assignment
            if ($user->isManager() || $user->isAdmin()) {
                $validated['assigned_by'] = $user->id;
                $validated['tipe'] = 'assigned';
            } else {
                $validated['tipe'] = 'self';
            }

            Aktivitas::create($validated);

            return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aktivitas = Aktivitas::with('rmft')->findOrFail($id);
        return view('aktivitas.show', compact('aktivitas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        // RMFT tidak bisa edit
        if ($user->isRMFT()) {
            abort(403, 'RMFT tidak memiliki akses untuk mengedit aktivitas.');
        }
        
        $aktivitas = Aktivitas::findOrFail($id);
        
        // Manager hanya bisa edit aktivitas di KC mereka (Admin bisa edit semua)
        if ($user->isManager() && $aktivitas->kode_kc != $user->kode_kanca) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('aktivitas.edit', compact('aktivitas'));
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
        $user = Auth::user();
        
        // RMFT tidak bisa update
        if ($user->isRMFT()) {
            abort(403, 'RMFT tidak memiliki akses untuk mengupdate aktivitas.');
        }
        
        $aktivitas = Aktivitas::findOrFail($id);
        
        // Manager hanya bisa update aktivitas di KC mereka (Admin bisa update semua)
        if ($user->isManager() && $aktivitas->kode_kc != $user->kode_kanca) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'rencana_aktivitas' => 'required|string',
            'segmen_nasabah' => 'required|string',
            'nama_nasabah' => 'required|string',
            'norek' => 'required|string',
            'rp_jumlah' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $aktivitas->update($validated);

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        // RMFT tidak bisa delete
        if ($user->isRMFT()) {
            abort(403, 'RMFT tidak memiliki akses untuk menghapus aktivitas.');
        }
        
        $aktivitas = Aktivitas::findOrFail($id);
        
        // Manager hanya bisa delete aktivitas di KC mereka (Admin bisa delete semua)
        if ($user->isManager() && $aktivitas->kode_kc != $user->kode_kanca) {
            abort(403, 'Unauthorized action.');
        }
        
        $aktivitas->delete();

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil dihapus!');
    }
    
    /**
     * Show feedback form
     */
    public function feedback($id)
    {
        $user = Auth::user();
        
        // Hanya RMFT yang bisa memberikan feedback
        if (!$user->isRMFT()) {
            abort(403, 'Hanya RMFT yang bisa memberikan feedback.');
        }
        
        $aktivitas = Aktivitas::findOrFail($id);
        
        // RMFT hanya bisa feedback aktivitas mereka sendiri
        if ($aktivitas->rmft_id != $user->rmft_id) {
            abort(403, 'Anda hanya bisa memberikan feedback untuk aktivitas Anda sendiri.');
        }
        
        return view('aktivitas.feedback', compact('aktivitas'));
    }
    
    /**
     * Store feedback
     */
    public function storeFeedback(Request $request, $id)
    {
        $user = Auth::user();
        
        // Hanya RMFT yang bisa memberikan feedback
        if (!$user->isRMFT()) {
            abort(403, 'Hanya RMFT yang bisa memberikan feedback.');
        }
        
        $aktivitas = Aktivitas::findOrFail($id);
        
        // RMFT hanya bisa feedback aktivitas mereka sendiri
        if ($aktivitas->rmft_id != $user->rmft_id) {
            abort(403, 'Anda hanya bisa memberikan feedback untuk aktivitas Anda sendiri.');
        }
        
        $validated = $request->validate([
            'status_realisasi' => 'required|in:tercapai,tidak_tercapai,lebih',
            'nominal_realisasi' => 'required_if:status_realisasi,tidak_tercapai,lebih|nullable|string',
            'keterangan_realisasi' => 'nullable|string',
        ]);
        
        // Jika tercapai, gunakan rp_jumlah sebagai nominal_realisasi
        if ($validated['status_realisasi'] === 'tercapai') {
            $validated['nominal_realisasi'] = $aktivitas->rp_jumlah;
        }
        
        $validated['tanggal_feedback'] = now();
        
        $aktivitas->update($validated);
        
        return redirect()->route('aktivitas.index')->with('success', 'Feedback berhasil disimpan!');
    }
}
