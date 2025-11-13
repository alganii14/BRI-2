@extends('layouts.app')

@section('title', 'Detail Nasabah')
@section('page-title', 'Detail Nasabah')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-header h3 {
        margin: 0;
        color: #333;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
    }

    .detail-value {
        color: #333;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-info {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
        color: white;
    }

    .btn {
        padding: 10px 24px;
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
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.4);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .aktivitas-section {
        margin-top: 32px;
    }

    .aktivitas-section h4 {
        margin-bottom: 16px;
        color: #333;
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
    }

    th {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
</style>

<div class="card">
    <div class="detail-header">
        <h3>Detail Nasabah</h3>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('nasabah.edit', $nasabah->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus nasabah ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Norek</div>
        <div class="detail-value"><strong>{{ $nasabah->norek }}</strong></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Nama Nasabah</div>
        <div class="detail-value">{{ $nasabah->nama_nasabah }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Segmen</div>
        <div class="detail-value">
            <span class="badge badge-info">{{ $nasabah->segmen_nasabah }}</span>
        </div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Kode KC</div>
        <div class="detail-value">{{ $nasabah->kode_kc ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Nama KC</div>
        <div class="detail-value">{{ $nasabah->nama_kc ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Kode Unit Kerja</div>
        <div class="detail-value">{{ $nasabah->kode_uker ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Nama Unit Kerja</div>
        <div class="detail-value">{{ $nasabah->nama_uker ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Telepon</div>
        <div class="detail-value">{{ $nasabah->telepon ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Alamat</div>
        <div class="detail-value">{{ $nasabah->alamat ?? '-' }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Dibuat</div>
        <div class="detail-value">{{ $nasabah->created_at->format('d M Y H:i') }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Terakhir Update</div>
        <div class="detail-value">{{ $nasabah->updated_at->format('d M Y H:i') }}</div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('nasabah.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

@if($nasabah->aktivitas->count() > 0)
<div class="card aktivitas-section">
    <h4>Riwayat Aktivitas ({{ $nasabah->aktivitas->count() }})</h4>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama RMFT</th>
                <th>Rencana Aktivitas</th>
                <th>RP/Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nasabah->aktivitas as $aktivitas)
            <tr>
                <td>{{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') }}</td>
                <td>{{ $aktivitas->nama_rmft }}</td>
                <td>{{ $aktivitas->rencana_aktivitas }}</td>
                <td>{{ number_format($aktivitas->rp_jumlah, 0, ',', '.') }}</td>
                <td>{{ $aktivitas->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
