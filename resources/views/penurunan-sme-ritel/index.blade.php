@extends('layouts.app')

@section('title', 'Penurunan SME Ritel')
@section('page-title', 'Data Penurunan SME Ritel')

@section('content')
<style>
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    tbody tr:hover {
        background-color: #f8f9ff;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-warning {
        background: #ffc107;
        color: #333;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    .delta-negative {
        color: #dc3545;
        font-weight: bold;
    }

    .delta-positive {
        color: #28a745;
        font-weight: bold;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        margin-right: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .action-buttons-top {
        margin-bottom: 20px;
    }

    .pagination-wrapper {
        margin-top: 30px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        text-align: center;
    }

    .pagination-info {
        color: #666;
        font-size: 14px;
        margin: 0;
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
</style>

<div class="action-buttons-top">
    <a href="{{ route('penurunan-sme-ritel.import.form') }}" class="btn btn-success">
        📤 Import CSV
    </a>
    <a href="{{ route('penurunan-sme-ritel.create') }}" class="btn btn-primary">
        ➕ Tambah Data
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="search-box">
    <form action="{{ route('penurunan-sme-ritel.index') }}" method="GET">
        <input type="text" name="search" placeholder="🔍 Cari berdasarkan nama nasabah, no rekening, CIFNO, atau unit kerja..." value="{{ $search }}">
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Regional Office</th>
                <th>Cabang Induk</th>
                <th>Unit Kerja</th>
                <th>CIFNO</th>
                <th>No Rekening</th>
                <th>Nama Nasabah</th>
                <th>Segmentasi BPR</th>
                <th>Delta</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->regional_office }}</td>
                    <td>{{ $item->cabang_induk }}</td>
                    <td>{{ $item->unit_kerja }}</td>
                    <td>{{ $item->cifno }}</td>
                    <td>{{ $item->no_rekening }}</td>
                    <td>{{ $item->nama_nasabah }}</td>
                    <td>{{ $item->segmentasi_bpr }}</td>
                    <td>
                        @php
                            $deltaValue = str_replace([',', '"'], '', $item->delta);
                            $deltaClass = (float)$deltaValue < 0 ? 'delta-negative' : 'delta-positive';
                        @endphp
                        <span class="{{ $deltaClass }}">{{ $item->delta }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('penurunan-sme-ritel.show', $item->id) }}" class="btn-sm btn-info">👁️ Lihat</a>
                            <a href="{{ route('penurunan-sme-ritel.edit', $item->id) }}" class="btn-sm btn-warning">✏️ Edit</a>
                            <form action="{{ route('penurunan-sme-ritel.destroy', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️ Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($data->hasPages())
<div class="pagination-wrapper">
    <p class="pagination-info">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results</p>
    
    <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
        @if ($data->onFirstPage())
            <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">← Previous</span>
        @else
            <a href="{{ $data->appends(request()->query())->previousPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">← Previous</a>
        @endif

        {{-- Show pages 1 to 5 only --}}
        @php
            $currentPage = $data->currentPage();
            $lastPage = $data->lastPage();
            $startPage = 1;
            $endPage = min(5, $lastPage);
        @endphp

        @foreach (range($startPage, $endPage) as $page)
            @php $url = $data->appends(request()->query())->url($page); @endphp
            @if ($page == $currentPage)
                <span style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: 1px solid #667eea; border-radius: 4px;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">{{ $page }}</a>
            @endif
        @endforeach

        @if ($data->hasMorePages())
            <a href="{{ $data->appends(request()->query())->nextPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">Next →</a>
        @else
            <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">Next →</span>
        @endif
    </div>
</div>
@endif
@endsection
