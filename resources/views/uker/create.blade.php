@extends('layouts.app')

@section('title', 'Tambah Uker')
@section('page-title', 'Tambah Unit Kerja')

@section('content')
<div class="page-header">
    <h2>Tambah Unit Kerja Baru</h2>
    <p>Isi form di bawah untuk menambah data Uker</p>
</div>

<div class="card">
    <form action="{{ route('uker.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="kode_sub_kanca">Kode Sub Kanca <span class="required">*</span></label>
                <input type="text" id="kode_sub_kanca" name="kode_sub_kanca" value="{{ old('kode_sub_kanca') }}" required class="form-control @error('kode_sub_kanca') is-invalid @enderror">
                @error('kode_sub_kanca')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="sub_kanca">Sub Kanca <span class="required">*</span></label>
                <input type="text" id="sub_kanca" name="sub_kanca" value="{{ old('sub_kanca') }}" required class="form-control @error('sub_kanca') is-invalid @enderror">
                @error('sub_kanca')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="segment">Segment</label>
                <select id="segment" name="segment" class="form-control">
                    <option value="">Pilih Segment</option>
                    <option value="MIKRO" {{ old('segment') == 'MIKRO' ? 'selected' : '' }}>MIKRO</option>
                    <option value="RETAIL" {{ old('segment') == 'RETAIL' ? 'selected' : '' }}>RETAIL</option>
                    <option value="KOMERSIAL" {{ old('segment') == 'KOMERSIAL' ? 'selected' : '' }}>KOMERSIAL</option>
                </select>
            </div>

            <div class="form-group">
                <label for="kode_kanca">Kode Kanca</label>
                <input type="text" id="kode_kanca" name="kode_kanca" value="{{ old('kode_kanca') }}" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="kanca">Kanca</label>
                <input type="text" id="kanca" name="kanca" value="{{ old('kanca') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="kanwil">Kanwil</label>
                <input type="text" id="kanwil" name="kanwil" value="{{ old('kanwil') }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="kode_kanwil">Kode Kanwil</label>
            <input type="text" id="kode_kanwil" name="kode_kanwil" value="{{ old('kode_kanwil') }}" class="form-control">
        </div>

        <div class="form-actions">
            <a href="{{ route('uker.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 14px;
    }

    .required {
        color: #f44336;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    .form-control.is-invalid {
        border-color: #f44336;
    }

    .error-message {
        color: #f44336;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .btn {
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
