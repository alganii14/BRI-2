@extends('layouts.app')

@section('title', 'Edit Rencana Aktivitas')
@section('page-title', 'Edit Rencana Aktivitas')

@section('content')
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .btn {
        padding: 10px 20px;
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

    .invalid-feedback {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Rencana Aktivitas</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('rencana-aktivitas.update', $rencanaAktivitas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama_rencana">Nama Rencana <span style="color: red;">*</span></label>
                <input type="text" name="nama_rencana" id="nama_rencana" class="form-control @error('nama_rencana') is-invalid @enderror" value="{{ old('nama_rencana', $rencanaAktivitas->nama_rencana) }}" required>
                @error('nama_rencana')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $rencanaAktivitas->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('rencana-aktivitas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
