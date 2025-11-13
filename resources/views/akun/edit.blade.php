@extends('layouts.app')

@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

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

    .form-group label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #0066CC;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 13px;
        margin-top: 4px;
        display: block;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 102, 204, 0.4);
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
        margin-top: 32px;
    }

    .card-header {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
        color: white;
        padding: 16px 20px;
        border-radius: 8px 8px 0 0;
        font-size: 16px;
        font-weight: 600;
    }

    .card-body {
        padding: 24px;
    }

    .help-text {
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }

    #rmftFields {
        display: none;
        padding: 16px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-top: 12px;
    }

    #managerFields {
        display: none;
        padding: 16px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-top: 12px;
    }

    .alert-info {
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
</style>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <i class="bi bi-pencil-square"></i> Edit Akun: {{ $akun->name }}
        </div>
        <div class="card-body">
            <form action="{{ route('akun.update', $akun->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $akun->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $akun->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert-info">
                    <strong>Info:</strong> Kosongkan password jika tidak ingin mengubahnya
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    <div class="help-text">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="role">Role <span class="required">*</span></label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $akun->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role', $akun->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="rmft" {{ old('role', $akun->role) == 'rmft' ? 'selected' : '' }}>RMFT</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Fields khusus RMFT -->
                <div id="rmftFields">
                    <div class="form-group">
                        <label for="pernr">PERNR</label>
                        <input type="text" name="pernr" id="pernr" class="form-control @error('pernr') is-invalid @enderror" 
                               value="{{ old('pernr', $akun->pernr) }}">
                        @error('pernr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="rmft_id">Data RMFT</label>
                        <select name="rmft_id" id="rmft_id" class="form-control @error('rmft_id') is-invalid @enderror">
                            <option value="">-- Pilih Data RMFT (Opsional) --</option>
                            @foreach($rmftList as $rmft)
                                <option value="{{ $rmft->id }}" {{ old('rmft_id', $akun->rmft_id) == $rmft->id ? 'selected' : '' }}>
                                    {{ $rmft->pernr }} - {{ $rmft->completename }} ({{ $rmft->kanca }})
                                </option>
                            @endforeach
                        </select>
                        <div class="help-text">Pilih jika ingin menghubungkan dengan data RMFT yang sudah ada</div>
                        @error('rmft_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Fields khusus Manager -->
                <div id="managerFields">
                    <div class="form-group">
                        <label for="kode_kanca">Kode Kanca</label>
                        <input type="text" name="kode_kanca" id="kode_kanca" class="form-control @error('kode_kanca') is-invalid @enderror" 
                               value="{{ old('kode_kanca', $akun->kode_kanca) }}">
                        @error('kode_kanca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_kanca">Nama Kanca</label>
                        <input type="text" name="nama_kanca" id="nama_kanca" class="form-control @error('nama_kanca') is-invalid @enderror" 
                               value="{{ old('nama_kanca', $akun->nama_kanca) }}">
                        @error('nama_kanca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update
                    </button>
                    <a href="{{ route('akun.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const rmftFields = document.getElementById('rmftFields');
    const managerFields = document.getElementById('managerFields');
    
    // Reset display
    rmftFields.style.display = 'none';
    managerFields.style.display = 'none';
    
    // Show relevant fields
    if (role === 'rmft') {
        rmftFields.style.display = 'block';
    } else if (role === 'manager') {
        managerFields.style.display = 'block';
    }
});

// Trigger on page load
window.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection
