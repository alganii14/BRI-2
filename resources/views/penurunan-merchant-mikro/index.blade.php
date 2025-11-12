@extends('layouts.app')

@section('title', 'Penurunan Merchant Mikro')
@section('page-title', 'Data Penurunan Merchant Mikro')

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

    .btn-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }

    .search-box input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title h2 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .page-actions {
        display: flex;
        gap: 10px;
    }
</style>

<div class="page-header">
    <div class="page-title">
        <h2>Data Penurunan Merchant Mikro</h2>
    </div>
    <div class="page-actions">
        <a href="{{ route('penurunan-merchant-mikro.create') }}" class="btn btn-primary btn-sm">➕ Tambah Data</a>
        <a href="{{ route('penurunan-merchant-mikro.import.form') }}" class="btn btn-success btn-sm">📥 Import CSV</a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        ✗ {{ session('error') }}
    </div>
@endif

<div class="search-box">
    <form method="GET" action="{{ route('penurunan-merchant-mikro.index') }}" style="display: flex; gap: 10px; flex: 1;">
        <select name="year" style="padding:10px 16px;border:1px solid #ddd;border-radius:6px;font-size:14px;background:white;min-width:140px;">
            <option value="">Semua Tahun</option>
            @foreach($availableYears as $availableYear)
                <option value="{{ $availableYear }}" {{ request('year') == $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
            @endforeach
        </select>
        <select name="month" style="padding:10px 16px;border:1px solid #ddd;border-radius:6px;font-size:14px;background:white;min-width:140px;">
            <option value="">Semua Bulan</option>
            @for($i=1;$i<=12;$i++)
                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$i] }}</option>
            @endfor
        </select>
        <input type="text" name="search" placeholder="Cari nama nasabah, no rekening, CIFNO, unit kerja..." value="{{ $search ?? '' }}" style="flex:1;">
        <button type="submit" class="btn btn-primary btn-sm">🔍 Cari</button>
        @if($search || request('month') || request('year'))
            <a href="{{ route('penurunan-merchant-mikro.index') }}" class="btn btn-danger btn-sm">✕ Reset</a>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Regional Office</th>
                <th>Unit Kerja</th>
                <th>CIFNO</th>
                <th>No Rekening</th>
                <th>Nama Nasabah</th>
                <th>Jenis Nasabah</th>
                <th>Saldo Last EOM</th>
                <th>Saldo Terupdate</th>
                <th>Delta</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item->regional_office }}</td>
                    <td>{{ $item->unit_kerja }}</td>
                    <td>{{ $item->cifno }}</td>
                    <td>{{ $item->no_rekening }}</td>
                    <td><strong>{{ $item->nama_nasabah }}</strong></td>
                    <td>{{ $item->jenis_nasabah }}</td>
                    <td style="text-align: right;">{{ $item->saldo_last_eom }}</td>
                    <td style="text-align: right;">{{ $item->saldo_terupdate }}</td>
                    <td style="text-align: right; color: {{ strpos($item->delta, '-') === 0 ? '#dc3545' : '#28a745' }}; font-weight: 600;">
                        {{ $item->delta }}
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('penurunan-merchant-mikro.show', $item->id) }}" class="btn btn-info btn-sm">👁️ Lihat</a>
                            <a href="{{ route('penurunan-merchant-mikro.edit', $item->id) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <form action="{{ route('penurunan-merchant-mikro.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px;">
    {{-- Previous Button --}}
    @if($data->onFirstPage())
        <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed;">← Sebelumnya</span>
    @else
        <a href="{{ $data->previousPageUrl() }}" class="btn btn-primary btn-sm">← Sebelumnya</a>
    @endif

    {{-- Page Numbers --}}
    @foreach (range(1, min(5, $lastPage)) as $page)
        @if ($page == $data->currentPage())
            <span class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">{{ $page }}</span>
        @else
            <a href="{{ $data->url($page) }}" class="btn" style="background: #f5f5f5; color: #667eea;">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next Button --}}
    @if($data->hasMorePages())
        <a href="{{ $data->nextPageUrl() }}" class="btn btn-primary btn-sm">Selanjutnya →</a>
    @else
        <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed;">Selanjutnya →</span>
    @endif
</div>

@endsection
