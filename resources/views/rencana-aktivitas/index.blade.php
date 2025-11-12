@extends('layouts.app')

@section('title', 'Rencana Aktivitas')
@section('page-title', 'Rencana Aktivitas')

@section('content')
<style>
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-warning {
        background-color: #ff9800;
        color: white;
    }

    .btn-warning:hover {
        background-color: #f57c00;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    .scroll-hint {
        display: none;
        text-align: center;
        padding: 8px;
        background: #f8f9fa;
        color: #666;
        font-size: 12px;
        border-top: 1px solid #dee2e6;
        margin-top: -1px;
    }

    .scroll-hint i {
        margin: 0 5px;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        -webkit-overflow-scrolling: touch;
        width: 100%;
        position: relative;
    }

    .table-container > div {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Scrollbar for table container */
    .table-container::-webkit-scrollbar {
        height: 8px;
    }

    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: white;
        overflow: hidden;
    }

    .card-header {
        padding: 20px;
        background: white;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-body {
        padding: 0;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .card-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }

        .card-header .btn {
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .card-header h5 {
            width: 100%;
        }
    }

    /* Tablet Portrait & Landscape */
    @media (min-width: 769px) and (max-width: 1024px) {
        .card-body {
            padding: 15px !important;
        }

        .scroll-hint {
            display: block;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }

        /* Scroll indicator shadow */
        .table-container > div {
            background:
                linear-gradient(90deg, white 30%, rgba(255,255,255,0)),
                linear-gradient(90deg, rgba(255,255,255,0), white 70%) 100% 0,
                radial-gradient(farthest-side at 0% 50%, rgba(0,0,0,.2), rgba(0,0,0,0)),
                radial-gradient(farthest-side at 100% 50%, rgba(0,0,0,.2), rgba(0,0,0,0)) 100% 0;
            background-repeat: no-repeat;
            background-color: white;
            background-size: 40px 100%, 40px 100%, 14px 100%, 14px 100%;
            background-attachment: local, local, scroll, scroll;
        }

        .table {
            font-size: 14px;
            min-width: 900px;
        }

        .table th,
        .table td {
            padding: 12px 14px;
            white-space: nowrap;
        }

        .btn {
            padding: 9px 18px;
            font-size: 13px;
        }

        .btn-sm {
            padding: 5px 11px;
            font-size: 12px;
        }

        .badge {
            padding: 3px 7px;
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 15px;
        }

        .card-body {
            padding: 15px !important;
        }

        .scroll-hint {
            display: block;
            font-size: 11px;
        }

        .card-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px;
        }

        .card-header h5 {
            font-size: 16px;
        }

        .card-header .btn {
            width: 100%;
            text-align: center;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: -15px;
            padding: 15px;
        }

        .table {
            min-width: 600px;
            font-size: 13px;
        }

        .table th,
        .table td {
            padding: 10px 12px;
            white-space: nowrap;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .badge {
            padding: 3px 7px;
            font-size: 11px;
        }

        .pagination-wrapper {
            padding: 15px !important;
            margin-top: 20px !important;
        }

        .pagination-info {
            font-size: 12px !important;
        }

        .pagination-wrapper > div {
            flex-wrap: wrap;
            gap: 5px !important;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 8px 15px !important;
            font-size: 12px !important;
        }

        /* Hide text on very small screens, show only icons/arrows */
        .pagination-wrapper a:not([href*="page"]),
        .pagination-wrapper span:not([style*="background: linear-gradient"]):not([style*="background: #f0f0f0"]) {
            min-width: auto;
        }
    }

    @media (max-width: 480px) {
        .card {
            border-radius: 6px;
        }

        .card-header {
            padding: 12px;
        }

        .card-body {
            padding: 12px !important;
        }

        .card-header h5 {
            font-size: 15px;
        }

        .table {
            font-size: 12px;
            min-width: 550px;
        }

        .table th,
        .table td {
            padding: 8px 10px;
        }

        .table th {
            font-size: 11px;
        }

        .btn {
            padding: 8px 15px;
            font-size: 12px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        .btn-sm i {
            font-size: 10px;
        }

        .badge {
            padding: 3px 6px;
            font-size: 10px;
        }

        .pagination-wrapper {
            padding: 10px !important;
            margin-top: 15px !important;
        }

        .pagination-info {
            font-size: 11px !important;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="mb-0" style="margin: 0;">Daftar Rencana Aktivitas</h5>
        <a href="{{ route('rencana-aktivitas.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 5px;">
            <i class="bi bi-plus-circle"></i> Tambah Rencana Aktivitas
        </a>
    </div>
    <div class="card-body" style="padding: 20px;">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <div style="overflow-x: auto; position: relative;">
                <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Rencana</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rencanaAktivitas as $index => $item)
                        <tr>
                            <td>{{ $rencanaAktivitas->firstItem() + $index }}</td>
                            <td>{{ $item->nama_rencana }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <a href="{{ route('rencana-aktivitas.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('rencana-aktivitas.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rencana aktivitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data rencana aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="scroll-hint">
                ← Geser ke kanan dan kiri untuk melihat seluruh tabel →
            </div>
        </div>

        <div class="pagination-wrapper" style="margin-top: 30px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <p class="pagination-info" style="color: #666; font-size: 14px; margin: 0;">
                Showing {{ $rencanaAktivitas->firstItem() ?? 0 }} to {{ $rencanaAktivitas->lastItem() ?? 0 }} of {{ $rencanaAktivitas->total() }} results
            </p>
            
            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
                @if ($rencanaAktivitas->onFirstPage())
                    <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">← Previous</span>
                @else
                    <a href="{{ $rencanaAktivitas->previousPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">← Previous</a>
                @endif

                {{-- Show pages 1 to 5 only --}}
                @php
                    $currentPage = $rencanaAktivitas->currentPage();
                    $lastPage = $rencanaAktivitas->lastPage();
                    $startPage = 1;
                    $endPage = min(5, $lastPage);
                @endphp

                @foreach (range($startPage, $endPage) as $page)
                    @php $url = $rencanaAktivitas->url($page); @endphp
                    @if ($page == $currentPage)
                        <span style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: 1px solid #667eea; border-radius: 4px;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($rencanaAktivitas->hasMorePages())
                    <a href="{{ $rencanaAktivitas->nextPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">Next →</a>
                @else
                    <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">Next →</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
