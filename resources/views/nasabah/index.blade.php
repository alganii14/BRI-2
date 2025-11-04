@extends('layouts.app')

@section('title', 'Nasabah')
@section('page-title', 'Nasabah')

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

    .btn-primary:hover {
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

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
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

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
    }

    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
    }

    .pagination a:hover {
        background-color: #f0f0f0;
    }

    .pagination .active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
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
            <p style="color: #666; font-size: 14px; margin-top: 4px;">Total: {{ $nasabahs->total() }} nasabah</p>
        </div>
        <a href="{{ route('nasabah.create') }}" class="btn btn-primary">
            + Tambah Nasabah
        </a>
    </div>

    @if($nasabahs->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Norek</th>
                    <th>Nama Nasabah</th>
                    <th>Segmen</th>
                    <th>KC</th>
                    <th>Unit Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nasabahs as $index => $nasabah)
                <tr>
                    <td>{{ $nasabahs->firstItem() + $index }}</td>
                    <td><strong>{{ $nasabah->norek }}</strong></td>
                    <td>{{ $nasabah->nama_nasabah }}</td>
                    <td><span class="badge badge-info">{{ $nasabah->segmen_nasabah }}</span></td>
                    <td>{{ $nasabah->nama_kc ?? '-' }}</td>
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

    <div class="pagination">
        {{ $nasabahs->links() }}
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
