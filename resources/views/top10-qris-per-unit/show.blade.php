@extends('layouts.app')

@section('title', 'Detail Top 10 QRIS Per Unit')
@section('page-title', 'Detail Data Top 10 QRIS Per Unit')

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
    <div class="section-title">Informasi Merchant & Branch</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Rank</div>
            <div class="detail-value">{{ $top10QrisPerUnit->rank ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Posisi</div>
            <div class="detail-value">{{ $top10QrisPerUnit->posisi ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Main Branch</div>
            <div class="detail-value">{{ $top10QrisPerUnit->mainbr ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">MB Desc</div>
            <div class="detail-value">{{ $top10QrisPerUnit->mbdesc ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Branch</div>
            <div class="detail-value">{{ $top10QrisPerUnit->branch ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">BR Desc</div>
            <div class="detail-value">{{ $top10QrisPerUnit->brdesc ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Store ID</div>
            <div class="detail-value">{{ $top10QrisPerUnit->storeid ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Nama Merchant</div>
            <div class="detail-value">{{ $top10QrisPerUnit->nama_merchant ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">Informasi Rekening & PIC</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">No Rekening</div>
            <div class="detail-value">{{ $top10QrisPerUnit->no_rek ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">CIF</div>
            <div class="detail-value">{{ $top10QrisPerUnit->cif ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN</div>
            <div class="detail-value">{{ $top10QrisPerUnit->pn ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">PN Pemrakasa</div>
            <div class="detail-value">{{ $top10QrisPerUnit->pn_pemrakasa ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">Informasi Transaksi QRIS</div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Akumulasi SV Total</div>
            <div class="detail-value">{{ $top10QrisPerUnit->akumulasi_sv_total ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Posisi SV Total September</div>
            <div class="detail-value">{{ $top10QrisPerUnit->posisi_sv_total_september ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Akumulasi TRX Total</div>
            <div class="detail-value">{{ $top10QrisPerUnit->akumulasi_trx_total ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Posisi TRX Total</div>
            <div class="detail-value">{{ $top10QrisPerUnit->posisi_trx_total ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Saldo Posisi</div>
            <div class="detail-value">{{ $top10QrisPerUnit->saldo_posisi ?? '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Ratas Saldo</div>
            <div class="detail-value">{{ $top10QrisPerUnit->ratas_saldo ?? '-' }}</div>
        </div>

        <div class="detail-item full-width">
            <div class="detail-label">Alamat</div>
            <div class="detail-value">{{ $top10QrisPerUnit->alamat ?? '-' }}</div>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <div class="actions">
            <a href="{{ route('top10-qris-per-unit.edit', $top10QrisPerUnit->id) }}" class="btn btn-warning">✏️ Edit</a>
            <a href="{{ route('top10-qris-per-unit.index') }}" class="btn btn-secondary">↩️ Kembali</a>
            <form action="{{ route('top10-qris-per-unit.destroy', $top10QrisPerUnit->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">🗑️ Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
