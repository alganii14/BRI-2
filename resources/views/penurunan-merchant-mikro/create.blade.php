@extends('layouts.app')

@section('title', 'Tambah Penurunan Merchant Mikro')
@section('page-title', 'Tambah Data Penurunan Merchant Mikro')

@section('content')
<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1200px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-top: 20px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
</style>

<div class="form-container">
    <h2 style="margin-bottom: 20px;">Tambah Data Penurunan Merchant Mikro</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penurunan-merchant-mikro.store') }}" method="POST">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label for="regional_office">Regional Office</label>
                <input type="text" id="regional_office" name="regional_office" value="{{ old('regional_office') }}">
            </div>

            <div class="form-group">
                <label for="kode_cabang_induk">Kode Cabang Induk</label>
                <input type="text" id="kode_cabang_induk" name="kode_cabang_induk" value="{{ old('kode_cabang_induk') }}">
            </div>

            <div class="form-group">
                <label for="cabang_induk">Cabang Induk</label>
                <input type="text" id="cabang_induk" name="cabang_induk" value="{{ old('cabang_induk') }}">
            </div>

            <div class="form-group">
                <label for="kode_uker">Kode Uker</label>
                <input type="text" id="kode_uker" name="kode_uker" value="{{ old('kode_uker') }}">
            </div>

            <div class="form-group">
                <label for="unit_kerja">Unit Kerja</label>
                <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}">
            </div>

            <div class="form-group">
                <label for="cifno">CIFNO</label>
                <input type="text" id="cifno" name="cifno" value="{{ old('cifno') }}">
            </div>

            <div class="form-group">
                <label for="no_rekening">No Rekening</label>
                <input type="text" id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}">
            </div>

            <div class="form-group">
                <label for="brilink">Brilink</label>
                <input type="text" id="brilink" name="brilink" value="{{ old('brilink') }}">
            </div>

            <div class="form-group">
                <label for="ytd">YTD</label>
                <input type="text" id="ytd" name="ytd" value="{{ old('ytd') }}">
            </div>

            <div class="form-group">
                <label for="product_type">Product Type</label>
                <input type="text" id="product_type" name="product_type" value="{{ old('product_type') }}">
            </div>

            <div class="form-group full-width">
                <label for="nama_nasabah">Nama Nasabah</label>
                <input type="text" id="nama_nasabah" name="nama_nasabah" value="{{ old('nama_nasabah') }}">
            </div>

            <div class="form-group">
                <label for="jenis_nasabah">Jenis Nasabah</label>
                <input type="text" id="jenis_nasabah" name="jenis_nasabah" value="{{ old('jenis_nasabah') }}">
            </div>

            <div class="form-group">
                <label for="segmentasi_bpr">Segmentasi BPR</label>
                <input type="text" id="segmentasi_bpr" name="segmentasi_bpr" value="{{ old('segmentasi_bpr') }}">
            </div>

            <div class="form-group">
                <label for="jenis_simpanan">Jenis Simpanan</label>
                <input type="text" id="jenis_simpanan" name="jenis_simpanan" value="{{ old('jenis_simpanan') }}">
            </div>

            <div class="form-group">
                <label for="saldo_last_eom">Saldo Last EOM</label>
                <input type="text" id="saldo_last_eom" name="saldo_last_eom" value="{{ old('saldo_last_eom') }}">
            </div>

            <div class="form-group">
                <label for="saldo_terupdate">Saldo Terupdate</label>
                <input type="text" id="saldo_terupdate" name="saldo_terupdate" value="{{ old('saldo_terupdate') }}">
            </div>

            <div class="form-group">
                <label for="delta">Delta</label>
                <input type="text" id="delta" name="delta" value="{{ old('delta') }}">
            </div>

            <div class="form-group full-width">
                <h3 class="section-title">PN Slots</h3>
                <div class="form-grid">
                    @for($i = 1; $i <= 8; $i++)
                        <div class="form-group">
                            <label for="pn_slot_{{ $i }}">PN Slot {{ $i }}</label>
                            <input type="text" id="pn_slot_{{ $i }}" name="pn_slot_{{ $i }}" value="{{ old('pn_slot_' . $i) }}">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="{{ route('penurunan-merchant-mikro.index') }}" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

@endsection
