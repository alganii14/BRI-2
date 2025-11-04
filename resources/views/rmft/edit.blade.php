@extends('layouts.app')

@section('title', 'Edit RMFT')
@section('page-title', 'Edit RMFT')

@section('content')
<div class="page-header">
    <h2>Edit Data RMFT</h2>
    <p>Update informasi RMFT</p>
</div>

<div class="card">
    <form action="{{ route('rmft.update', $rmft->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label for="pernr">PERNR</label>
                <input type="text" id="pernr" name="pernr" value="{{ old('pernr', $rmft->pernr) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="completename">Nama Lengkap <span class="required">*</span></label>
                <input type="text" id="completename" name="completename" value="{{ old('completename', $rmft->completename) }}" required class="form-control @error('completename') is-invalid @enderror">
                @error('completename')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="jg">JG (Job Grade)</label>
                <input type="text" id="jg" name="jg" value="{{ old('jg', $rmft->jg) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="esgdesc">Status</label>
                <input type="text" id="esgdesc" name="esgdesc" value="{{ old('esgdesc', $rmft->esgdesc) }}" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="kanca">Kanca</label>
                <input type="text" id="kanca" name="kanca" value="{{ old('kanca', $rmft->kanca) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="uker_id">Relasi Uker (Optional)</label>
                <select id="uker_id" name="uker_id" class="form-control">
                    <option value="">Pilih Uker Kanca</option>
                    @foreach($ukers as $uker)
                        <option value="{{ $uker->id }}" {{ old('uker_id', $rmft->uker_id) == $uker->id ? 'selected' : '' }}>
                            {{ $uker->kanca }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="uker">Uker Saat Ini</label>
                <input type="text" id="uker" name="uker" value="{{ old('uker', $rmft->uker) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="uker_tujuan">Uker Tujuan</label>
                <input type="text" id="uker_tujuan" name="uker_tujuan" value="{{ old('uker_tujuan', $rmft->uker_tujuan) }}" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="kelompok_jabatan">Kelompok Jabatan</label>
                <input type="text" id="kelompok_jabatan" name="kelompok_jabatan" value="{{ old('kelompok_jabatan', $rmft->kelompok_jabatan) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $rmft->keterangan) }}" class="form-control">
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('rmft.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
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
