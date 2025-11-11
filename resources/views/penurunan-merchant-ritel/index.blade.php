@extends('layouts.app')

@section('content')
<style>
    .gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-input {
        flex: 1;
        padding: 10px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-import {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .btn-import:hover {
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 3000px;
        table-layout: auto;
    }

    thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    th {
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        min-width: 100px;
    }

    td {
        padding: 14px 12px;
        border-bottom: 1px solid #e0e0e0;
        font-size: 13px;
        min-width: 100px;
        max-width: 200px;
        word-wrap: break-word;
    }

    tbody tr:hover {
        background: #f8f9ff;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
    }

    .btn-view:hover {
        background: #1976d2;
        color: white;
    }

    .btn-edit {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .btn-edit:hover {
        background: #7b1fa2;
        color: white;
    }

    .btn-delete {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-delete:hover {
        background: #d32f2f;
        color: white;
    }

    .btn-delete-all {
        background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-delete-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.4);
    }

    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding: 20px 0;
    }

    .pagination-btn {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .pagination-btn:hover {
        border-color: #667eea;
        color: #667eea;
    }

    .pagination-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
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
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #2e7d32;
    }

    .alert-error {
        background: #ffebee;
        color: #d32f2f;
        border-left: 4px solid #d32f2f;
    }

    .delta-positive {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .delta-negative {
        background: #ffebee;
        color: #d32f2f;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 24px;
        border-radius: 12px;
        max-width: 400px;
        text-align: center;
    }

    .modal-content h3 {
        margin: 0 0 16px;
        color: #333;
    }

    .modal-content p {
        color: #666;
        margin: 0 0 24px;
    }

    .modal-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .modal-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
    }

    .modal-btn-cancel {
        background: #e0e0e0;
        color: #333;
    }

    .modal-btn-confirm {
        background: #d32f2f;
        color: white;
    }
</style>

<div class="gradient-header">
    <h1>Penurunan Merchant Ritel</h1>
    <p>Kelola data penurunan merchant ritel dari aktivitas bisnis</p>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">{{ $message }}</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-error">{{ $message }}</div>
@endif

<div class="search-container">
    <form style="flex: 1; display: flex; gap: 12px;">
        <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan nama nasabah, no rekening, CIFNO, atau unit kerja..." value="{{ $search }}">
        <button type="submit" class="btn-primary">Cari</button>
    </form>
    <a href="{{ route('penurunan-merchant-ritel.create') }}" class="btn-primary">+ Tambah Data</a>
    <a href="{{ route('penurunan-merchant-ritel.import.form') }}" class="btn-primary btn-import">📥 Import CSV</a>
    <button type="button" class="btn-delete-all" onclick="confirmDeleteAll()">🗑️ Hapus Semua</button>
</div>

<div class="table-container">
    @if ($data->count() > 0)
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Regional Office</th>
                <th>Kode Cabang Induk</th>
                <th>Cabang Induk</th>
                <th>Kode UKER</th>
                <th>Unit Kerja</th>
                <th>CIFNO</th>
                <th>No. Rekening</th>
                <th>Penurunan</th>
                <th>Product Type</th>
                <th>Nama Nasabah</th>
                <th>Segmentasi BPR</th>
                <th>Jenis Simpanan</th>
                <th>Saldo Last EOM</th>
                <th>Saldo Terupdate</th>
                <th>Delta</th>
                <th>PN Slot 1</th>
                <th>PN Slot 2</th>
                <th>PN Slot 3</th>
                <th>PN Slot 4</th>
                <th>PN Slot 5</th>
                <th>PN Slot 6</th>
                <th>PN Slot 7</th>
                <th>PN Slot 8</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                <td>{{ $item->regional_office ?? '-' }}</td>
                <td>{{ $item->kode_cabang_induk ?? '-' }}</td>
                <td>{{ $item->cabang_induk ?? '-' }}</td>
                <td>{{ $item->kode_uker ?? '-' }}</td>
                <td>{{ $item->unit_kerja ?? '-' }}</td>
                <td>{{ $item->cifno ?? '-' }}</td>
                <td>{{ $item->no_rekening ?? '-' }}</td>
                <td>{{ $item->penurunan ?? '-' }}</td>
                <td>{{ $item->product_type ?? '-' }}</td>
                <td>{{ $item->nama_nasabah ?? '-' }}</td>
                <td>{{ $item->segmentasi_bpr ?? '-' }}</td>
                <td>{{ $item->jenis_simpanan ?? '-' }}</td>
                <td>{{ $item->saldo_last_eom ?? '-' }}</td>
                <td>{{ $item->saldo_terupdate ?? '-' }}</td>
                <td>
                    @if ($item->delta)
                        @if ((float)$item->delta < 0)
                            <span class="delta-negative">{{ $item->delta }}</span>
                        @else
                            <span class="delta-positive">{{ $item->delta }}</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->pn_slot_1 ?? '-' }}</td>
                <td>{{ $item->pn_slot_2 ?? '-' }}</td>
                <td>{{ $item->pn_slot_3 ?? '-' }}</td>
                <td>{{ $item->pn_slot_4 ?? '-' }}</td>
                <td>{{ $item->pn_slot_5 ?? '-' }}</td>
                <td>{{ $item->pn_slot_6 ?? '-' }}</td>
                <td>{{ $item->pn_slot_7 ?? '-' }}</td>
                <td>{{ $item->pn_slot_8 ?? '-' }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('penurunan-merchant-ritel.show', $item->id) }}" class="btn-action btn-view">View</a>
                        <a href="{{ route('penurunan-merchant-ritel.edit', $item->id) }}" class="btn-action btn-edit">Edit</a>
                        <button class="btn-action btn-delete" onclick="openDeleteModal({{ $item->id }})">Delete</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($data->hasPages())
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

    @else
    <div class="no-data">
        <p>Belum ada data penurunan merchant ritel</p>
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Konfirmasi Penghapusan</h3>
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-confirm">Hapus</button>
            </form>
        </div>
    </div>
</div>

<!-- Delete All Modal -->
<div id="deleteAllModal" class="modal">
    <div class="modal-content">
        <h3>⚠️ Konfirmasi Hapus Semua Data</h3>
        <p style="color: #d32f2f; font-weight: 600;">PERHATIAN: Tindakan ini akan menghapus SEMUA data penurunan merchant ritel!</p>
        <p>Total data yang akan dihapus: <strong>{{ \App\Models\PenurunanMerchantRitel::count() }}</strong> record</p>
        <p>Apakah Anda yakin ingin melanjutkan?</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteAllModal()">Batal</button>
            <form id="deleteAllForm" method="POST" action="{{ route('penurunan-merchant-ritel.deleteAll') }}" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-confirm" style="background: #d32f2f;">Hapus Semua</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = '/penurunan-merchant-ritel/' + id;
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    function confirmDeleteAll() {
        document.getElementById('deleteAllModal').classList.add('show');
    }

    function closeDeleteAllModal() {
        document.getElementById('deleteAllModal').classList.remove('show');
    }

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('deleteModal');
        const deleteAllModal = document.getElementById('deleteAllModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
        if (event.target === deleteAllModal) {
            closeDeleteAllModal();
        }
    });
</script>
@endsection
