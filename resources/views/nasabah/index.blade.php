@extends('layouts.app')

@section('title', 'Nasabah')
@section('page-title', 'Nasabah')

@section('content')
<style>
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        max-width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 13px;
    }

    th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 13px;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-danger-gradient {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-danger-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
    }

    .btn-import {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .btn-import:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-info {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
</style>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="header-actions">
        <div>
            <h3>Daftar Nasabah</h3>
            <p style="color: #666; font-size: 14px; margin-top: 4px;">
                Menampilkan {{ $nasabahs->count() }} nasabah per halaman
            </p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('nasabah.import.form') }}" class="btn btn-import">
                📤 Import MTH
            </a>
            <a href="{{ route('nasabah.create') }}" class="btn btn-primary">
                + Tambah Nasabah
            </a>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div style="margin-bottom: 20px;">
        <form action="{{ route('nasabah.index') }}" method="GET" id="filterForm">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333;">
                        🔍 Cari Nasabah
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari berdasarkan norek, CIFNO, atau nama (min. 2 karakter)..."
                        style="width: 100%; padding: 10px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    >
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333;">
                        🏢 Filter KC
                    </label>
                    <select 
                        name="kode_kc" 
                        id="filterKC"
                        style="width: 100%; padding: 10px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        onchange="this.form.submit()"
                    >
                        <option value="">Semua KC</option>
                        @foreach($kcList as $kc)
                        <option value="{{ $kc->kode_kc }}" {{ request('kode_kc') == $kc->kode_kc ? 'selected' : '' }}>
                            {{ $kc->kode_kc }} - {{ $kc->nama_kc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333;">
                        🏪 Filter Unit
                    </label>
                    <select 
                        name="kode_uker" 
                        id="filterUnit"
                        style="width: 100%; padding: 10px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        onchange="this.form.submit()"
                        {{ !request('kode_kc') ? 'disabled' : '' }}
                    >
                        <option value="">Semua Unit</option>
                        @foreach($ukerList as $uker)
                        <option value="{{ $uker->kode_uker }}" {{ request('kode_uker') == $uker->kode_uker ? 'selected' : '' }}>
                            {{ $uker->kode_uker }} - {{ $uker->nama_uker }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #333;">
                        📄 Per Halaman
                    </label>
                    <select 
                        name="per_page" 
                        style="width: 100%; padding: 10px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        onchange="this.form.submit()"
                    >
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                    🔍 Cari
                </button>
                @if(request('search') || request('kode_kc') || request('kode_uker'))
                <a href="{{ route('nasabah.index') }}" class="btn" style="background: #6c757d; color: white; white-space: nowrap;">
                    ✕ Reset Filter
                </a>
                @endif
                
                @if(request('search') || request('kode_kc') || request('kode_uker'))
                <p style="margin: 0; font-size: 13px; color: #666;">
                    @if(request('search'))
                    Pencarian: <strong>"{{ request('search') }}"</strong>
                    @endif
                    @if(request('kode_kc'))
                    | KC: <strong>{{ request('kode_kc') }}</strong>
                    @endif
                    @if(request('kode_uker'))
                    | Unit: <strong>{{ request('kode_uker') }}</strong>
                    @endif
                </p>
                @endif
            </div>
        </form>
    </div>

    @if($nasabahs->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 120px;">Norek</th>
                    <th style="width: 120px;">CIFNO</th>
                    <th style="min-width: 180px;">Nama Nasabah</th>
                    <th style="width: 100px;">Segmen</th>
                    <th style="width: 80px;">Kode KC</th>
                    <th style="min-width: 200px;">Nama KC</th>
                    <th style="width: 90px;">Kode Uker</th>
                    <th style="min-width: 150px;">Nama Uker</th>
                    <th style="width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nasabahs as $index => $nasabah)
                <tr>
                    <td><strong>{{ $nasabah->norek }}</strong></td>
                    <td><strong>{{ $nasabah->cifno ?? '-' }}</strong></td>
                    <td>{{ $nasabah->nama_nasabah }}</td>
                    <td><span class="badge badge-info">{{ $nasabah->segmen_nasabah }}</span></td>
                    <td><strong style="color: #667eea;">{{ $nasabah->kode_kc ?? '-' }}</strong></td>
                    <td>{{ $nasabah->nama_kc ?? '-' }}</td>
                    <td><strong style="color: #764ba2;">{{ $nasabah->kode_uker ?? '-' }}</strong></td>
                    <td>{{ $nasabah->nama_uker ?? '-' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('nasabah.show', $nasabah->id) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('nasabah.edit', $nasabah->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus nasabah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="margin-top: 20px;">
        <p style="text-align: center; color: #666; font-size: 14px;">
            Menampilkan {{ $nasabahs->count() }} nasabah
        </p>
        
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
            @if ($nasabahs->onFirstPage())
                <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">← Previous</span>
            @else
                <a href="{{ $nasabahs->appends(request()->except('page'))->previousPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">← Previous</a>
            @endif

            <span style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: 1px solid #667eea; border-radius: 4px;">
                Halaman {{ $nasabahs->currentPage() }}
            </span>

            @if ($nasabahs->hasMorePages())
                <a href="{{ $nasabahs->appends(request()->except('page'))->nextPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">Next →</a>
            @else
                <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">Next →</span>
            @endif
        </div>
    </div>
    @else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h3>Belum ada data nasabah</h3>
        <p>Klik tombol "Tambah Nasabah" untuk menambahkan nasabah baru</p>
    </div>
    @endif
</div>
@endsection
