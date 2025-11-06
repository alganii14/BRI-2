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

    .detail-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-top: 24px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #667eea;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title:first-of-type {
        margin-top: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        padding: 12px;
        background: #f8f9ff;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .detail-value.empty {
        color: #999;
        font-style: italic;
    }

    .delta-positive {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .delta-negative {
        background: #ffebee;
        color: #d32f2f;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .delta-neutral {
        background: #f5f5f5;
        color: #666;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back {
        background: #e0e0e0;
        color: #333;
    }

    .btn-back:hover {
        background: #d0d0d0;
    }

    .btn-edit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-delete {
        background: #d32f2f;
        color: white;
    }

    .btn-delete:hover {
        background: #b71c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
    }

    .info-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
    }

    .badge-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-warning {
        background: #fff3e0;
        color: #f57c00;
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

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="gradient-header">
    <h1>Detail Data Penurunan Merchant Ritel</h1>
    <p>Informasi lengkap dari data penurunan merchant ritel</p>
</div>

<div class="detail-container">
    <h3 class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Informasi Utama
    </h3>
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Regional Office</span>
            <span class="detail-value {{ $data->regional_office ? '' : 'empty' }}">
                {{ $data->regional_office ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Kode Cabang Induk</span>
            <span class="detail-value {{ $data->kode_cabang_induk ? '' : 'empty' }}">
                {{ $data->kode_cabang_induk ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Cabang Induk</span>
            <span class="detail-value {{ $data->cabang_induk ? '' : 'empty' }}">
                {{ $data->cabang_induk ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Kode UKER</span>
            <span class="detail-value {{ $data->kode_uker ? '' : 'empty' }}">
                {{ $data->kode_uker ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Unit Kerja</span>
            <span class="detail-value {{ $data->unit_kerja ? '' : 'empty' }}">
                {{ $data->unit_kerja ?? 'Tidak ada data' }}
            </span>
        </div>
    </div>

    <h3 class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Data Nasabah
    </h3>
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">CIFNO</span>
            <span class="detail-value {{ $data->cifno ? '' : 'empty' }}">
                {{ $data->cifno ?? 'Tidak ada data' }}
            </span>
            @if($data->cifno)
                <span class="info-badge badge-primary">ID Pelanggan</span>
            @endif
        </div>

        <div class="detail-item">
            <span class="detail-label">No. Rekening</span>
            <span class="detail-value {{ $data->no_rekening ? '' : 'empty' }}">
                {{ $data->no_rekening ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Nama Nasabah</span>
            <span class="detail-value {{ $data->nama_nasabah ? '' : 'empty' }}">
                {{ $data->nama_nasabah ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Penurunan</span>
            <span class="detail-value {{ $data->penurunan ? '' : 'empty' }}">
                {{ $data->penurunan ?? 'Tidak ada data' }}
            </span>
            @if($data->penurunan)
                <span class="info-badge badge-warning">Status Penurunan</span>
            @endif
        </div>

        <div class="detail-item">
            <span class="detail-label">Product Type</span>
            <span class="detail-value {{ $data->product_type ? '' : 'empty' }}">
                {{ $data->product_type ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Segmentasi BPR</span>
            <span class="detail-value {{ $data->segmentasi_bpr ? '' : 'empty' }}">
                {{ $data->segmentasi_bpr ?? 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Jenis Simpanan</span>
            <span class="detail-value {{ $data->jenis_simpanan ? '' : 'empty' }}">
                {{ $data->jenis_simpanan ?? 'Tidak ada data' }}
            </span>
        </div>
    </div>

    <h3 class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Data Keuangan
    </h3>
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Saldo Last EOM</span>
            <span class="detail-value {{ $data->saldo_last_eom ? '' : 'empty' }}">
                @if($data->saldo_last_eom)
                    Rp {{ number_format((float)$data->saldo_last_eom, 0, ',', '.') }}
                @else
                    Tidak ada data
                @endif
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Saldo Terupdate</span>
            <span class="detail-value {{ $data->saldo_terupdate ? '' : 'empty' }}">
                @if($data->saldo_terupdate)
                    Rp {{ number_format((float)$data->saldo_terupdate, 0, ',', '.') }}
                @else
                    Tidak ada data
                @endif
            </span>
            @if($data->saldo_terupdate)
                <span class="info-badge badge-success">Saldo Aktif</span>
            @endif
        </div>

        <div class="detail-item">
            <span class="detail-label">Delta (Perubahan)</span>
            @if($data->delta)
                @php
                    $deltaValue = (float)$data->delta;
                @endphp
                @if($deltaValue < 0)
                    <span class="delta-negative">
                        ⬇ Rp {{ number_format($deltaValue, 0, ',', '.') }}
                    </span>
                @elseif($deltaValue > 0)
                    <span class="delta-positive">
                        ⬆ Rp {{ number_format($deltaValue, 0, ',', '.') }}
                    </span>
                @else
                    <span class="delta-neutral">
                        = Rp {{ number_format($deltaValue, 0, ',', '.') }}
                    </span>
                @endif
            @else
                <span class="detail-value empty">Tidak ada data</span>
            @endif
        </div>
    </div>

    <h3 class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        PN Slots (Program Nasabah)
    </h3>
    <div class="detail-grid">
        @for ($i = 1; $i <= 8; $i++)
        <div class="detail-item">
            <span class="detail-label">PN Slot {{ $i }}</span>
            <span class="detail-value {{ $data->{'pn_slot_' . $i} ? '' : 'empty' }}">
                {{ $data->{'pn_slot_' . $i} ?? 'Tidak ada data' }}
            </span>
        </div>
        @endfor
    </div>

    <h3 class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Informasi Sistem
    </h3>
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Tanggal Dibuat</span>
            <span class="detail-value">
                {{ $data->created_at ? $data->created_at->format('d F Y, H:i') : 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Terakhir Diupdate</span>
            <span class="detail-value">
                {{ $data->updated_at ? $data->updated_at->format('d F Y, H:i') : 'Tidak ada data' }}
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">ID Record</span>
            <span class="detail-value">
                #{{ $data->id }}
            </span>
            <span class="info-badge badge-primary">Database ID</span>
        </div>
    </div>

    <div class="button-group">
        <a href="{{ route('penurunan-merchant-ritel.index') }}" class="btn btn-back">
            ← Kembali ke Daftar
        </a>
        <a href="{{ route('penurunan-merchant-ritel.edit', $data->id) }}" class="btn btn-edit">
            ✏️ Edit Data
        </a>
        <button class="btn btn-delete" onclick="openDeleteModal()">
            🗑️ Hapus Data
        </button>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Konfirmasi Penghapusan</h3>
        <p>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <form method="POST" action="{{ route('penurunan-merchant-ritel.destroy', $data->id) }}" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-confirm">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    });
</script>
@endsection
