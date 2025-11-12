<?php

namespace App\Http\Controllers;

use App\Models\RencanaAktivitas;
use Illuminate\Http\Request;

class RencanaAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rencanaAktivitas = RencanaAktivitas::latest()->paginate(10);
        return view('rencana-aktivitas.index', compact('rencanaAktivitas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rencana-aktivitas.create');
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
            'nama_rencana' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        RencanaAktivitas::create($validated);

        return redirect()->route('rencana-aktivitas.index')
            ->with('success', 'Rencana Aktivitas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RencanaAktivitas  $rencanaAktivitas
     * @return \Illuminate\Http\Response
     */
    public function show(RencanaAktivitas $rencanaAktivitas)
    {
        return view('rencana-aktivitas.show', compact('rencanaAktivitas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RencanaAktivitas  $rencanaAktivitas
     * @return \Illuminate\Http\Response
     */
    public function edit(RencanaAktivitas $rencanaAktivitas)
    {
        return view('rencana-aktivitas.edit', compact('rencanaAktivitas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RencanaAktivitas  $rencanaAktivitas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RencanaAktivitas $rencanaAktivitas)
    {
        $validated = $request->validate([
            'nama_rencana' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $rencanaAktivitas->update($validated);

        return redirect()->route('rencana-aktivitas.index')
            ->with('success', 'Rencana Aktivitas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RencanaAktivitas  $rencanaAktivitas
     * @return \Illuminate\Http\Response
     */
    public function destroy(RencanaAktivitas $rencanaAktivitas)
    {
        $rencanaAktivitas->delete();

        return redirect()->route('rencana-aktivitas.index')
            ->with('success', 'Rencana Aktivitas berhasil dihapus!');
    }
}
