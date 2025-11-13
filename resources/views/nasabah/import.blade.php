@extends('layouts.app')

@section('title', 'Import Nasabah MTH')
@section('page-title', 'Import Nasabah MTH')

@section('content')
<style>
    .import-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 30px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafafa;
    }

    .file-input-label:hover {
        border-color: #0066CC;
        background: #f0f4ff;
    }

    .file-input-label.has-file {
        border-color: #0066CC;
        background: #f0f4ff;
    }

    .file-icon {
        width: 48px;
        height: 48px;
        margin-right: 16px;
        opacity: 0.5;
    }

    .file-text {
        text-align: left;
    }

    .file-text-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .file-text-subtitle {
        font-size: 13px;
        color: #666;
    }

    .selected-file {
        margin-top: 12px;
        padding: 12px;
        background: #e8f5e9;
        border-radius: 6px;
        font-size: 14px;
        color: #2e7d32;
        display: none;
    }

    .selected-file.show {
        display: block;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0066CC 0%, #003D82 100%);
        color: white;
        width: 100%;
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        margin-right: 12px;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }

    .info-box h4 {
        margin: 0 0 8px 0;
        color: #1976d2;
        font-size: 14px;
        font-weight: 600;
    }

    .info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #0d47a1;
        font-size: 13px;
    }

    .info-box ul li {
        margin-bottom: 4px;
    }

    .actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
</style>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error">
    {{ session('error') }}
</div>
@endif

<div class="import-container">
    <div class="card">
        <h2 style="margin-bottom: 8px;">Import Data Nasabah MTH</h2>
        <p style="color: #666; font-size: 14px; margin-bottom: 24px;">Upload file CSV untuk mengimport data nasabah dari MTH</p>

        <div class="info-box">
            <h4>ℹ️ Informasi Penting:</h4>
            <ul>
                <li>Format file: CSV (.csv)</li>
                <li>Maksimal ukuran file via browser: <strong>100 MB</strong></li>
                <li><strong>⚠️ Untuk file > 100 MB (contoh: 800 MB), gunakan Command Line!</strong></li>
                <li>Cara import via Command Line: <code>php artisan import:nasabah "path/to/file.csv"</code></li>
                <li>Baca dokumentasi lengkap: <code>IMPORT_800MB_CSV.md</code></li>
                <li>Pastikan struktur CSV sesuai dengan format MTH standar</li>
                <li>Kode KC dan Kode Uker akan otomatis dikonversi (menghilangkan leading zero)</li>
                <li>Contoh: 00405 → 405, 00593 → 593</li>
                <li>Data duplikat (berdasarkan norek) akan diabaikan</li>
                <li>Proses import untuk data besar dapat memakan waktu beberapa menit</li>
                <li><strong>Jangan tutup halaman</strong> selama proses import berlangsung</li>
            </ul>
        </div>

        <div class="alert" style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; margin-bottom: 24px;">
            <strong>💡 Tips untuk File Besar (> 100 MB):</strong><br>
            Gunakan command line untuk performa lebih baik:<br>
            <code style="background: #fff; padding: 8px; display: block; margin-top: 8px; border-radius: 4px;">
                php artisan import:nasabah "C:\path\to\your\large-file.csv"
            </code>
            <small>Import via command line bisa handle file 800 MB+ dengan kecepatan ~1500 records/detik</small>
        </div>

        <form action="{{ route('nasabah.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf

            <div class="form-group">
                <label>File CSV</label>
                <div class="file-input-wrapper">
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" required onchange="handleFileSelect(this)">
                    <label for="csv_file" class="file-input-label" id="fileLabel">
                        <svg class="file-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div class="file-text">
                            <div class="file-text-title">Klik untuk memilih file CSV</div>
                            <div class="file-text-subtitle">atau drag & drop file di sini</div>
                        </div>
                    </label>
                </div>
                <div class="selected-file" id="selectedFile">
                    <strong>File terpilih:</strong> <span id="fileName"></span>
                </div>
                @error('csv_file')
                <div style="color: #dc3545; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="actions">
                <a href="{{ route('nasabah.index') }}" class="btn btn-secondary">← Kembali</a>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="flex: 1;">
                    <span id="btnText">📤 Upload & Import</span>
                    <span id="btnLoading" style="display: none;">⏳ Sedang mengimport...</span>
                </button>
            </div>
        </form>

        @php
            $totalNasabah = \App\Models\Nasabah::count();
        @endphp

        @if($totalNasabah > 0)
        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #f0f0f0;">
            <h3 style="color: #dc3545; margin-bottom: 16px;">⚠️ Zona Berbahaya</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 16px;">
                Menghapus semua data nasabah akan menghapus <strong>{{ number_format($totalNasabah, 0, ',', '.') }} data nasabah</strong> secara permanen dan tidak dapat dikembalikan.
            </p>
            <form action="{{ route('nasabah.delete-all') }}" method="POST" onsubmit="return confirm('⚠️ PERINGATAN KERAS!\n\nAnda akan menghapus SEMUA {{ number_format($totalNasabah, 0, ",", ".") }} data nasabah!\n\nData yang sudah dihapus TIDAK DAPAT dikembalikan!\n\nApakah Anda BENAR-BENAR yakin?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    🗑️ Hapus Semua Data Nasabah
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
function handleFileSelect(input) {
    const fileLabel = document.getElementById('fileLabel');
    const selectedFile = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 100 * 1024 * 1024; // 100 MB in bytes
        
        // Check file size
        if (file.size > maxSize) {
            alert('⚠️ Ukuran file terlalu besar!\n\nUkuran file: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB\nMaksimal: 100 MB\n\nSilakan pilih file yang lebih kecil atau hubungi administrator.');
            input.value = '';
            fileLabel.classList.remove('has-file');
            selectedFile.classList.remove('show');
            submitBtn.disabled = true;
            return;
        }
        
        fileLabel.classList.add('has-file');
        fileName.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
        selectedFile.classList.add('show');
        submitBtn.disabled = false;
    } else {
        fileLabel.classList.remove('has-file');
        selectedFile.classList.remove('show');
        submitBtn.disabled = true;
    }
}

document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('csv_file');
    
    if (!fileInput.files || !fileInput.files[0]) {
        e.preventDefault();
        alert('⚠️ Silakan pilih file CSV terlebih dahulu!');
        return false;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
    
    submitBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';
});

// Drag and drop functionality
const fileLabel = document.getElementById('fileLabel');
const fileInput = document.getElementById('csv_file');

fileLabel.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('has-file');
});

fileLabel.addEventListener('dragleave', function() {
    if (!fileInput.files.length) {
        this.classList.remove('has-file');
    }
});

fileLabel.addEventListener('drop', function(e) {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length) {
        fileInput.files = files;
        handleFileSelect(fileInput);
    }
});

// Disable submit button on load
document.getElementById('submitBtn').disabled = true;
</script>
@endsection
