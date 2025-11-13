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
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
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

    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    .table thead {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
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

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"></h5>
        <a href="{{ route('rencana-aktivitas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Rencana Aktivitas
        </a>
    </div>
    <br>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-container">
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
                                <a href="{{ route('rencana-aktivitas.edit', $item->id) }}" class="btn btn-warning btn-sm" style="margin-right: 5px;">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('rencana-aktivitas.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rencana aktivitas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
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
                        <span style="padding: 10px 20px; background: linear-gradient(135deg, #0066CC 0%, #003D82 100%); color: white; border: 1px solid #0066CC; border-radius: 4px;">{{ $page }}</span>
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
