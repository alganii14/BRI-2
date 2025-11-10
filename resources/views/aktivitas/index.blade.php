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

@if(auth()->user()->isAdmin())
<div class="card" style="margin-bottom: 20px;">
    <form method="GET" action="{{ route('aktivitas.index') }}" style="display: flex; gap: 15px; align-items: flex-end;">
        <div style="flex: 1;">
            <label for="kode_kc" style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px;">Filter per KC</label>
            <select name="kode_kc" id="kode_kc" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
                <option value="">-- Semua KC --</option>
                @foreach($listKC as $kc)
                <option value="{{ $kc->kode_kc }}" {{ request('kode_kc') == $kc->kode_kc ? 'selected' : '' }}>
                    {{ $kc->kode_kc }} - {{ $kc->nama_kc }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1;">
            <label for="kode_uker" style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px;">Filter per Unit</label>
            <select name="kode_uker" id="kode_uker" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 100%;">
                <option value="">-- Semua Unit --</option>
                @foreach($listUnit as $unit)
                <option value="{{ $unit->kode_uker }}" data-kc="{{ $unit->kode_kc }}" {{ request('kode_uker') == $unit->kode_uker ? 'selected' : '' }}>
                    {{ $unit->kode_uker }} - {{ $unit->nama_uker }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('aktivitas.index') }}" class="btn" style="background-color: #6c757d; color: white;">Reset</a>
        </div>
    </form>
</div>

<script>
// Filter unit berdasarkan KC yang dipilih
document.getElementById('kode_kc').addEventListener('change', function() {
    var selectedKC = this.value;
    var unitSelect = document.getElementById('kode_uker');
    var allOptions = unitSelect.querySelectorAll('option');
    
    allOptions.forEach(function(option) {
        if (option.value === '') {
            option.style.display = 'block';
        } else {
            var optionKC = option.getAttribute('data-kc');
            if (selectedKC === '' || optionKC === selectedKC) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    });
    
    // Reset unit selection jika tidak sesuai dengan KC
    if (unitSelect.value !== '') {
        var selectedOption = unitSelect.querySelector('option[value="' + unitSelect.value + '"]');
        if (selectedOption && selectedOption.style.display === 'none') {
            unitSelect.value = '';
        }
    }
});
</script>
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
                    <th>CIFNO</th>
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
                            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
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
    <div class="pagination-wrapper">
        <p class="pagination-info">Showing {{ $aktivitas->firstItem() }} to {{ $aktivitas->lastItem() }} of {{ $aktivitas->total() }} results</p>
        
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
            @if ($aktivitas->onFirstPage())
                <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">← Previous</span>
            @else
                <a href="{{ $aktivitas->appends(request()->query())->previousPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">← Previous</a>
            @endif

            {{-- Show pages 1 to 5 only --}}
            @php
                $currentPage = $aktivitas->currentPage();
                $lastPage = $aktivitas->lastPage();
                $startPage = 1;
                $endPage = min(5, $lastPage);
            @endphp

            @foreach (range($startPage, $endPage) as $page)
                @php $url = $aktivitas->appends(request()->query())->url($page); @endphp
                @if ($page == $currentPage)
                    <span style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: 1px solid #667eea; border-radius: 4px;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">{{ $page }}</a>
                @endif
            @endforeach

            @if ($aktivitas->hasMorePages())
                <a href="{{ $aktivitas->appends(request()->query())->nextPageUrl() }}" style="padding: 10px 20px; background: white; color: #333; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; text-decoration: none;">Next →</a>
            @else
                <span style="padding: 10px 20px; background: #f0f0f0; color: #999; border: 1px solid #ddd; border-radius: 4px; cursor: not-allowed;">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
