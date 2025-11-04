@extends('layouts.app')

@section('title', 'Edit Aktivitas')
@section('page-title', 'Edit Aktivitas')

@section('content')
<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
    }

    .form-group input:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        margin: 24px -24px 20px -24px;
        font-size: 16px;
        font-weight: 600;
    }

    .section-header:first-child {
        margin-top: -24px;
    }

    .info-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }

    .info-box p {
        margin: 5px 0;
        font-size: 14px;
        color: #666;
    }

    .info-box strong {
        color: #333;
    }
</style>

<div class="card">
    <div class="section-header">
        Edit Aktivitas
    </div>

    <div class="info-box">
        <p><strong>RMFT:</strong> {{ $aktivitas->nama_rmft }}</p>
        <p><strong>PN:</strong> {{ $aktivitas->pn }}</p>
        <p><strong>Kanca:</strong> {{ $aktivitas->nama_kc }}</p>
    </div>

    <form action="{{ route('aktivitas.update', $aktivitas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>TANGGAL <span style="color: red;">*</span></label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $aktivitas->tanggal->format('Y-m-d')) }}" required>
        </div>

        <div class="section-header">
            Data Aktivitas
        </div>

        <div class="form-group">
            <label>RENCANA AKTIVITAS <span style="color: red;">*</span></label>
            <select name="rencana_aktivitas" required>
                <option value="">Pilih Rencana Aktivitas</option>
                <option value="DANA MASUK TABUNGAN (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'DANA MASUK TABUNGAN (Rp)' ? 'selected' : '' }}>DANA MASUK TABUNGAN (Rp)</option>
                <option value="PICKUP SERVICE (RP)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'PICKUP SERVICE (RP)' ? 'selected' : '' }}>PICKUP SERVICE (RP)</option>
                <option value="DANA MASUK GIRO (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'DANA MASUK GIRO (Rp)' ? 'selected' : '' }}>DANA MASUK GIRO (Rp)</option>
                <option value="DANA MASUK DEPO (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'DANA MASUK DEPO (Rp)' ? 'selected' : '' }}>DANA MASUK DEPO (Rp)</option>
                <option value="BRICUAN (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'BRICUAN (Rp)' ? 'selected' : '' }}>BRICUAN (Rp)</option>
                <option value="EXTRA REWARD PRIO (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'EXTRA REWARD PRIO (Rp)' ? 'selected' : '' }}>EXTRA REWARD PRIO (Rp)</option>
                <option value="BOOSTER DEPO (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'BOOSTER DEPO (Rp)' ? 'selected' : '' }}>BOOSTER DEPO (Rp)</option>
                <option value="PANEN EXTRA TAB (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'PANEN EXTRA TAB (Rp)' ? 'selected' : '' }}>PANEN EXTRA TAB (Rp)</option>
                <option value="BRING BACK FUND (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'BRING BACK FUND (Rp)' ? 'selected' : '' }}>BRING BACK FUND (Rp)</option>
                <option value="CASA BRILINK (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'CASA BRILINK (Rp)' ? 'selected' : '' }}>CASA BRILINK (Rp)</option>
                <option value="DISBURSEMENT KREDIT MIKRO (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'DISBURSEMENT KREDIT MIKRO (Rp)' ? 'selected' : '' }}>DISBURSEMENT KREDIT MIKRO (Rp)</option>
                <option value="NASI KUNING (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'NASI KUNING (Rp)' ? 'selected' : '' }}>NASI KUNING (Rp)</option>
                <option value="MERCY (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'MERCY (Rp)' ? 'selected' : '' }}>MERCY (Rp)</option>
                <option value="SHL SMT II (Rp)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'SHL SMT II (Rp)' ? 'selected' : '' }}>SHL SMT II (Rp)</option>
                <option value="AKURASI (Jumlah Rek)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'AKURASI (Jumlah Rek)' ? 'selected' : '' }}>AKURASI (Jumlah Rek)</option>
                <option value="REK VQ (Jumlah Rek)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'REK VQ (Jumlah Rek)' ? 'selected' : '' }}>REK VQ (Jumlah Rek)</option>
                <option value="REK H3 (Jumlah Rek)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'REK H3 (Jumlah Rek)' ? 'selected' : '' }}>REK H3 (Jumlah Rek)</option>
                <option value="Akuisisi EDC/ QRIS (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Akuisisi EDC/ QRIS (Jumlah)' ? 'selected' : '' }}>Akuisisi EDC/ QRIS (Jumlah)</option>
                <option value="brifest spbu baraya (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'brifest spbu baraya (Jumlah)' ? 'selected' : '' }}>brifest spbu baraya (Jumlah)</option>
                <option value="lucky ride (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'lucky ride (Jumlah)' ? 'selected' : '' }}>lucky ride (Jumlah)</option>
                <option value="Menyala Agenku (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Menyala Agenku (Jumlah)' ? 'selected' : '' }}>Menyala Agenku (Jumlah)</option>
                <option value="Agen Ngebutz (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Agen Ngebutz (Jumlah)' ? 'selected' : '' }}>Agen Ngebutz (Jumlah)</option>
                <option value="Akuisisi Incoming (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Akuisisi Incoming (Jumlah)' ? 'selected' : '' }}>Akuisisi Incoming (Jumlah)</option>
                <option value="Akuisisi Hotspot (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Akuisisi Hotspot (Jumlah)' ? 'selected' : '' }}>Akuisisi Hotspot (Jumlah)</option>
                <option value="Giro Pareto Belum EDC (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Giro Pareto Belum EDC (Jumlah)' ? 'selected' : '' }}>Giro Pareto Belum EDC (Jumlah)</option>
                <option value="Giro Reward (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Giro Reward (Jumlah)' ? 'selected' : '' }}>Giro Reward (Jumlah)</option>
                <option value="Suku Bunga Nego Giro (Jumlah)" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Suku Bunga Nego Giro (Jumlah)' ? 'selected' : '' }}>Suku Bunga Nego Giro (Jumlah)</option>
                <option value="Cross Sell Perusahaan Anak" {{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) == 'Cross Sell Perusahaan Anak' ? 'selected' : '' }}>Cross Sell Perusahaan Anak</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>SEGMEN NASABAH <span style="color: red;">*</span></label>
                <select name="segmen_nasabah" required>
                    <option value="">Pilih Segmen</option>
                    <option value="Ritel Badan Usaha" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Ritel Badan Usaha' ? 'selected' : '' }}>Ritel Badan Usaha</option>
                    <option value="SME" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'SME' ? 'selected' : '' }}>SME</option>
                    <option value="Konsumer" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Konsumer' ? 'selected' : '' }}>Konsumer</option>
                    <option value="Prioritas" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Prioritas' ? 'selected' : '' }}>Prioritas</option>
                    <option value="Merchant" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    <option value="Agen Brilink" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Agen Brilink' ? 'selected' : '' }}>Agen Brilink</option>
                    <option value="Mikro" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Mikro' ? 'selected' : '' }}>Mikro</option>
                    <option value="Komersial" {{ old('segmen_nasabah', $aktivitas->segmen_nasabah) == 'Komersial' ? 'selected' : '' }}>Komersial</option>
                </select>
            </div>

            <div class="form-group">
                <label>NAMA NASABAH <span style="color: red;">*</span></label>
                <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah', $aktivitas->nama_nasabah) }}" required>
            </div>

            <div class="form-group">
                <label>NOREK <span style="color: red;">*</span></label>
                <input type="text" name="norek" value="{{ old('norek', $aktivitas->norek) }}" required>
            </div>

            <div class="form-group">
                <label>RP / JUMLAH <span style="color: red;">*</span></label>
                <input type="text" name="rp_jumlah" value="{{ old('rp_jumlah', $aktivitas->rp_jumlah) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>KETERANGAN</label>
            <textarea name="keterangan" rows="3">{{ old('keterangan', $aktivitas->keterangan) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Aktivitas</button>
            <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
