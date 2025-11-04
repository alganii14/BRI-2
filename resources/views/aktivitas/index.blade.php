@extends('layouts.app')

@section('title', 'Aktivitas')
@section('page-title', 'Aktivitas')

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

    .badge-rmft {
        background-color: #4caf50;
        color: white;
    }

    .badge-assigned {
        background-color: #ff9800;
        color: white;
        font-size: 11px;
        padding: 3px 8px;
        margin-left: 8px;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }

    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
    }

    table tr:hover {
        background-color: #f8f9fa;
    }

    .action-buttons {
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
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>

<div class="page-header">
    <h2>Manajemen Aktivitas</h2>
    <p>Kelola semua aktivitas pipeline</p>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="header-actions">
        <h3>Daftar Aktivitas</h3>
        <a href="{{ route('aktivitas.create') }}" class="btn btn-primary">+ Tambah Aktivitas</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>NAMA RMFT</th>
                    <th>PN</th>
                    <th>KODE KC</th>
                    <th>NAMA KC</th>
                    <th>KELOMPOK</th>
                    <th>RENCANA AKTIVITAS</th>
                    <th>SEGMEN NASABAH</th>
                    <th>NAMA NASABAH</th>
                    <th>NOREK</th>
                    <th>TARGET</th>
                    <th>STATUS</th>
                    <th>REALISASI</th>
                    <th>KETERANGAN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aktivitas as $item)
                <tr>
                    <td>{{ $loop->iteration + ($aktivitas->currentPage() - 1) * $aktivitas->perPage() }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>
                        {{ $item->nama_rmft }}
                        @if($item->tipe == 'assigned')
                        <span class="badge badge-assigned">Assigned by {{ $item->assignedBy->name ?? 'Manager' }}</span>
                        @endif
                    </td>
                    <td>{{ $item->pn }}</td>
                    <td>{{ $item->kode_kc }}</td>
                    <td>{{ $item->nama_kc }}</td>
                    <td>{{ $item->kelompok }}</td>
                    <td>{{ $item->rencana_aktivitas }}</td>
                    <td>{{ $item->segmen_nasabah }}</td>
                    <td>{{ $item->nama_nasabah }}</td>
                    <td>{{ $item->norek }}</td>
                    <td>Rp {{ number_format($item->rp_jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if($item->status_realisasi == 'belum')
                        <span class="badge badge-warning">Belum</span>
                        @elseif($item->status_realisasi == 'tercapai')
                        <span class="badge badge-success">✅ Tercapai</span>
                        @elseif($item->status_realisasi == 'tidak_tercapai')
                        <span class="badge badge-danger">❌ Tidak Tercapai</span>
                        @elseif($item->status_realisasi == 'lebih')
                        <span class="badge badge-info">🎉 Melebihi</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status_realisasi != 'belum')
                        Rp {{ number_format($item->nominal_realisasi, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            @if(auth()->user()->isManager())
                            <a href="{{ route('aktivitas.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('aktivitas.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @elseif(auth()->user()->isRMFT())
                                @if($item->status_realisasi == 'belum')
                                <a href="{{ route('aktivitas.feedback', $item->id) }}" class="btn btn-info btn-sm">Feedback</a>
                                @else
                                <span style="color: #28a745; font-size: 12px;">✓ Sudah Feedback</span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="16" style="text-align: center; padding: 40px; color: #666;">
                        Belum ada data aktivitas. <a href="{{ route('aktivitas.create') }}" style="color: #667eea;">Tambah aktivitas</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($aktivitas->hasPages())
    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $aktivitas->links() }}
    </div>
    @endif
</div>
@endsection
