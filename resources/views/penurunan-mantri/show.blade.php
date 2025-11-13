@extends('layouts.app')

@section('title', 'Detail Penurunan Mantri')
@section('page-title', 'Detail Data Penurunan Mantri')

@section('content')
<style>
    .detail-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1200px;
        margin: 0 auto;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        padding: 15px;
        background: #f8f9ff;
        border-radius: 8px;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        font-weight: 600;
        text-transform: uppercase;
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

    .delta-negative {
        color: #dc3545;
        font-weight: bold;
        font-size: 16px;
    }

    .delta-positive {
        color: #28a745;
        font-weight: bold;
        font-size: 16px;
    }

    .btn {
        padding: 12px 24px;
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

    .btn-warning {
        background: #ffc107;
        color: #333;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .detail-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-container">
    <div class="detail-section">
        <div class="section-title">📍 Informasi Umum</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Regional Office</div>
                <div class="detail-value {{ $penurunanMantri->regional_office ? '' : 'empty' }}">
                    {{ $penurunanMantri->regional_office ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Kode Cabang Induk</div>
                <div class="detail-value {{ $penurunanMantri->kode_cabang_induk ? '' : 'empty' }}">
                    {{ $penurunanMantri->kode_cabang_induk ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Cabang Induk</div>
                <div class="detail-value {{ $penurunanMantri->cabang_induk ? '' : 'empty' }}">
                    {{ $penurunanMantri->cabang_induk ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Kode Uker</div>
                <div class="detail-value {{ $penurunanMantri->kode_uker ? '' : 'empty' }}">
                    {{ $penurunanMantri->kode_uker ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Unit Kerja</div>
                <div class="detail-value {{ $penurunanMantri->unit_kerja ? '' : 'empty' }}">
                    {{ $penurunanMantri->unit_kerja ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">YTD</div>
                <div class="detail-value {{ $penurunanMantri->ytd ? '' : 'empty' }}">
                    {{ $penurunanMantri->ytd ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="section-title">👤 Informasi Nasabah</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">CIFNO</div>
                <div class="detail-value {{ $penurunanMantri->cifno ? '' : 'empty' }}">
                    {{ $penurunanMantri->cifno ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">No Rekening</div>
                <div class="detail-value {{ $penurunanMantri->no_rekening ? '' : 'empty' }}">
                    {{ $penurunanMantri->no_rekening ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Nama Nasabah</div>
                <div class="detail-value {{ $penurunanMantri->nama_nasabah ? '' : 'empty' }}">
                    {{ $penurunanMantri->nama_nasabah ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Jenis Nasabah</div>
                <div class="detail-value {{ $penurunanMantri->jenis_nasabah ? '' : 'empty' }}">
                    {{ $penurunanMantri->jenis_nasabah ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Product Type</div>
                <div class="detail-value {{ $penurunanMantri->product_type ? '' : 'empty' }}">
                    {{ $penurunanMantri->product_type ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Segmentasi BPR</div>
                <div class="detail-value {{ $penurunanMantri->segmentasi_bpr ? '' : 'empty' }}">
                    {{ $penurunanMantri->segmentasi_bpr ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Jenis Simpanan</div>
                <div class="detail-value {{ $penurunanMantri->jenis_simpanan ? '' : 'empty' }}">
                    {{ $penurunanMantri->jenis_simpanan ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="section-title">💰 Informasi Saldo</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Saldo Last EOM</div>
                <div class="detail-value {{ $penurunanMantri->saldo_last_eom ? '' : 'empty' }}">
                    {{ $penurunanMantri->saldo_last_eom ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Saldo Terupdate</div>
                <div class="detail-value {{ $penurunanMantri->saldo_terupdate ? '' : 'empty' }}">
                    {{ $penurunanMantri->saldo_terupdate ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Delta</div>
                @php
                    $deltaValue = str_replace([',', '"'], '', $penurunanMantri->delta);
                    $deltaClass = (float)$deltaValue < 0 ? 'delta-negative' : 'delta-positive';
                @endphp
                <div class="detail-value {{ $deltaClass }}">
                    {{ $penurunanMantri->delta ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <div class="section-title">📋 PN Slots</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">PN Slot 1</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_1 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_1 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 2</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_2 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_2 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 3</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_3 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_3 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 4</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_4 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_4 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 5</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_5 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_5 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 6</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_6 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_6 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 7</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_7 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_7 ?? '-' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PN Slot 8</div>
                <div class="detail-value {{ $penurunanMantri->pn_slot_8 ? '' : 'empty' }}">
                    {{ $penurunanMantri->pn_slot_8 ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="detail-actions">
        <a href="{{ route('penurunan-mantri.edit', $penurunanMantri->id) }}" class="btn btn-warning">
            ✏️ Edit
        </a>
        <a href="{{ route('penurunan-mantri.index') }}" class="btn btn-primary">
            ← Kembali
        </a>
        <form action="{{ route('penurunan-mantri.destroy', $penurunanMantri->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                🗑️ Hapus
            </button>
        </form>
    </div>
</div>

@endsection
