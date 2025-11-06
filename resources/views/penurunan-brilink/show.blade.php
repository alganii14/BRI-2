@extends('layouts.app')

@section('title', 'Detail Penurunan Brilink')
@section('page-title', 'Detail Data Penurunan Brilink')

@section('content')
<style>
    .detail-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1000px;
        margin: 0 auto;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        font-size: 13px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 16px;
        color: #333;
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
        margin-right: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .delta-negative {
        color: #dc3545;
        font-weight: 600;
    }

    .delta-positive {
        color: #28a745;
        font-weight: 600;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #667eea;
        margin: 30px 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }
</style>

<div class="detail-container">
    <div class="section-title">Informasi Umum</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Regional Office</div>
            <div class="detail-value">{{ $penurunanBrilink->regional_office ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Kode Cabang Induk</div>
            <div class="detail-value">{{ $penurunanBrilink->kode_cabang_induk ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Cabang Induk</div>
            <div class="detail-value">{{ $penurunanBrilink->cabang_induk ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Kode Uker</div>
            <div class="detail-value">{{ $penurunanBrilink->kode_uker ?? '-' }}</div>
        </div>

        <div class="detail-item full-width">
            <div class="detail-label">Unit Kerja</div>
            <div class="detail-value">{{ $penurunanBrilink->unit_kerja ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">Informasi Nasabah</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">CIFNO</div>
            <div class="detail-value">{{ $penurunanBrilink->cifno ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">No Rekening</div>
            <div class="detail-value">{{ $penurunanBrilink->no_rekening ?? '-' }}</div>
        </div>

        <div class="detail-item full-width">
            <div class="detail-label">Nama Nasabah</div>
            <div class="detail-value">{{ $penurunanBrilink->nama_nasabah ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Jenis Simpanan</div>
            <div class="detail-value">{{ $penurunanBrilink->jenis_simpanan ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Product Type</div>
            <div class="detail-value">{{ $penurunanBrilink->product_type ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">YTD</div>
            <div class="detail-value">{{ $penurunanBrilink->ytd ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">Informasi Saldo</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Saldo Last EOM</div>
            <div class="detail-value">{{ $penurunanBrilink->saldo_last_eom ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Saldo Terupdate</div>
            <div class="detail-value">{{ $penurunanBrilink->saldo_terupdate ?? '-' }}</div>
        </div>

        <div class="detail-item full-width">
            <div class="detail-label">Delta</div>
            <div class="detail-value {{ strpos($penurunanBrilink->delta, '-') === 0 ? 'delta-negative' : 'delta-positive' }}">
                {{ $penurunanBrilink->delta ?? '-' }}
            </div>
        </div>
    </div>

    <div class="section-title">PN Slots</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">PN Slot 1</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_1 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 2</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_2 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 3</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_3 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 4</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_4 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 5</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_5 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 6</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_6 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 7</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_7 ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Slot 8</div>
            <div class="detail-value">{{ $penurunanBrilink->pn_slot_8 ?? '-' }}</div>
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('penurunan-brilink.edit', $penurunanBrilink->id) }}" class="btn btn-warning">✏️ Edit</a>
        <a href="{{ route('penurunan-brilink.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </div>
</div>
@endsection
