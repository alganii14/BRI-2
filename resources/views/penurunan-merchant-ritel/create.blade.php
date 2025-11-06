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

    .form-container {
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
    }

    .section-title:first-of-type {
        margin-top: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .button-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
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
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .error-message {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-group.error input,
    .form-group.error select,
    .form-group.error textarea {
        border-color: #d32f2f;
    }
</style>

<div class="gradient-header">
    <h1>Tambah Data Penurunan Merchant Ritel</h1>
    <p>Isikan data baru untuk penurunan merchant ritel</p>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('penurunan-merchant-ritel.store') }}">
        @csrf

        <h3 class="section-title">📋 Informasi Utama</h3>
        <div class="form-grid">
            <div class="form-group @error('regional_office') error @enderror">
                <label for="regional_office">Regional Office</label>
                <input type="text" id="regional_office" name="regional_office" value="{{ old('regional_office') }}">
                @error('regional_office') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('kode_cabang_induk') error @enderror">
                <label for="kode_cabang_induk">Kode Cabang Induk</label>
                <input type="text" id="kode_cabang_induk" name="kode_cabang_induk" value="{{ old('kode_cabang_induk') }}">
                @error('kode_cabang_induk') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('cabang_induk') error @enderror">
                <label for="cabang_induk">Cabang Induk</label>
                <input type="text" id="cabang_induk" name="cabang_induk" value="{{ old('cabang_induk') }}">
                @error('cabang_induk') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('kode_uker') error @enderror">
                <label for="kode_uker">Kode UKER</label>
                <input type="text" id="kode_uker" name="kode_uker" value="{{ old('kode_uker') }}">
                @error('kode_uker') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('unit_kerja') error @enderror">
                <label for="unit_kerja">Unit Kerja</label>
                <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}">
                @error('unit_kerja') <span class="error-message">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 class="section-title">👤 Data Nasabah</h3>
        <div class="form-grid">
            <div class="form-group @error('cifno') error @enderror">
                <label for="cifno">CIFNO</label>
                <input type="text" id="cifno" name="cifno" value="{{ old('cifno') }}">
                @error('cifno') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('no_rekening') error @enderror">
                <label for="no_rekening">No. Rekening</label>
                <input type="text" id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}">
                @error('no_rekening') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('penurunan') error @enderror">
                <label for="penurunan">Penurunan</label>
                <input type="text" id="penurunan" name="penurunan" value="{{ old('penurunan') }}">
                @error('penurunan') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('product_type') error @enderror">
                <label for="product_type">Product Type</label>
                <input type="text" id="product_type" name="product_type" value="{{ old('product_type') }}">
                @error('product_type') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('nama_nasabah') error @enderror">
                <label for="nama_nasabah">Nama Nasabah</label>
                <input type="text" id="nama_nasabah" name="nama_nasabah" value="{{ old('nama_nasabah') }}">
                @error('nama_nasabah') <span class="error-message">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 class="section-title">💰 Data Keuangan</h3>
        <div class="form-grid">
            <div class="form-group @error('segmentasi_bpr') error @enderror">
                <label for="segmentasi_bpr">Segmentasi BPR</label>
                <input type="text" id="segmentasi_bpr" name="segmentasi_bpr" value="{{ old('segmentasi_bpr') }}">
                @error('segmentasi_bpr') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('jenis_simpanan') error @enderror">
                <label for="jenis_simpanan">Jenis Simpanan</label>
                <input type="text" id="jenis_simpanan" name="jenis_simpanan" value="{{ old('jenis_simpanan') }}">
                @error('jenis_simpanan') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('saldo_last_eom') error @enderror">
                <label for="saldo_last_eom">Saldo Last EOM</label>
                <input type="text" id="saldo_last_eom" name="saldo_last_eom" value="{{ old('saldo_last_eom') }}">
                @error('saldo_last_eom') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('saldo_terupdate') error @enderror">
                <label for="saldo_terupdate">Saldo Terupdate</label>
                <input type="text" id="saldo_terupdate" name="saldo_terupdate" value="{{ old('saldo_terupdate') }}">
                @error('saldo_terupdate') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group @error('delta') error @enderror">
                <label for="delta">Delta</label>
                <input type="text" id="delta" name="delta" value="{{ old('delta') }}">
                @error('delta') <span class="error-message">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 class="section-title">📊 PN Slots</h3>
        <div class="form-grid">
            @for ($i = 1; $i <= 8; $i++)
            <div class="form-group @error('pn_slot_' . $i) error @enderror">
                <label for="pn_slot_{{ $i }}">PN Slot {{ $i }}</label>
                <input type="text" id="pn_slot_{{ $i }}" name="pn_slot_{{ $i }}" value="{{ old('pn_slot_' . $i) }}">
                @error('pn_slot_' . $i) <span class="error-message">{{ $message }}</span> @enderror
            </div>
            @endfor
        </div>

        <div class="button-group">
            <a href="{{ route('penurunan-merchant-ritel.index') }}" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-submit">Simpan Data</button>
        </div>
    </form>
</div>

@endsection
