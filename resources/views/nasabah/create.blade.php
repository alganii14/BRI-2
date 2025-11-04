@extends('layouts.app')

@section('title', 'Tambah Nasabah')
@section('page-title', 'Tambah Nasabah')

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

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

    .alert {
        padding: 12px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert ul {
        margin: 8px 0 0 20px;
    }

    .required {
        color: red;
    }

    .form-help {
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }
</style>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Error:</strong>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <h3>Tambah Nasabah Baru</h3>

    <form action="{{ route('nasabah.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Norek <span class="required">*</span></label>
                <input type="text" name="norek" value="{{ old('norek') }}" required placeholder="Nomor rekening">
                <div class="form-help">Masukkan nomor rekening nasabah</div>
            </div>

            <div class="form-group">
                <label>Nama Nasabah <span class="required">*</span></label>
                <input type="text" name="nama_nasabah" value="{{ old('nama_nasabah') }}" required placeholder="Nama lengkap nasabah">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Segmen Nasabah <span class="required">*</span></label>
                <select name="segmen_nasabah" required>
                    <option value="">Pilih Segmen</option>
                    <option value="Ritel Badan Usaha" {{ old('segmen_nasabah') == 'Ritel Badan Usaha' ? 'selected' : '' }}>Ritel Badan Usaha</option>
                    <option value="SME" {{ old('segmen_nasabah') == 'SME' ? 'selected' : '' }}>SME</option>
                    <option value="Konsumer" {{ old('segmen_nasabah') == 'Konsumer' ? 'selected' : '' }}>Konsumer</option>
                    <option value="Prioritas" {{ old('segmen_nasabah') == 'Prioritas' ? 'selected' : '' }}>Prioritas</option>
                    <option value="Merchant" {{ old('segmen_nasabah') == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    <option value="Agen Brilink" {{ old('segmen_nasabah') == 'Agen Brilink' ? 'selected' : '' }}>Agen Brilink</option>
                    <option value="Mikro" {{ old('segmen_nasabah') == 'Mikro' ? 'selected' : '' }}>Mikro</option>
                    <option value="Komersial" {{ old('segmen_nasabah') == 'Komersial' ? 'selected' : '' }}>Komersial</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kode KC <span class="required">*</span></label>
                <input type="text" name="kode_kc" value="{{ old('kode_kc', auth()->user()->kode_kanca ?? '') }}" required placeholder="Kode Kantor Cabang">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama KC <span class="required">*</span></label>
                <input type="text" name="nama_kc" value="{{ old('nama_kc', auth()->user()->nama_kanca ?? '') }}" required placeholder="Nama Kantor Cabang">
            </div>

            <div class="form-group">
                <label>Kode Uker <span class="required">*</span></label>
                <input type="text" name="kode_uker" value="{{ old('kode_uker') }}" required placeholder="Kode Unit Kerja">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Uker <span class="required">*</span></label>
                <input type="text" name="nama_uker" value="{{ old('nama_uker') }}" required placeholder="Nama Unit Kerja">
            </div>

            <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Nomor telepon">
            </div>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" placeholder="Alamat lengkap nasabah">{{ old('alamat') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Nasabah</button>
            <a href="{{ route('nasabah.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
