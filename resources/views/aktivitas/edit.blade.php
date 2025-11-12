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
            <select name="rencana_aktivitas_id" required>
                <option value="">Pilih Rencana Aktivitas</option>
                @foreach($rencanaAktivitas as $item)
                    <option value="{{ $item->id }}" 
                            data-nama="{{ $item->nama_rencana }}"
                            {{ old('rencana_aktivitas_id', $aktivitas->rencana_aktivitas_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_rencana }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="rencana_aktivitas" value="{{ old('rencana_aktivitas', $aktivitas->rencana_aktivitas) }}">
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
                <label>CIFNO <span style="color: red;">*</span></label>
                <input type="text" name="norek" value="{{ old('norek', $aktivitas->norek) }}" required placeholder="CIFNO nasabah">
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

<script>
    // Event listener untuk update hidden field rencana_aktivitas
    document.querySelector('select[name="rencana_aktivitas_id"]').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const namaRencana = selectedOption.getAttribute('data-nama') || selectedOption.text;
        document.querySelector('input[name="rencana_aktivitas"]').value = namaRencana;
    });
</script>
@endsection
